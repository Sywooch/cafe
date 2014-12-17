<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Order;

/**
 * OrderSearch represents the model behind the search form about `app\models\Order`.
 */
class OrderSearch extends Order {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['order_id', 'pos_id', 'seller_id', 'sysuser_id', 'order_day_sequence_number'], 'integer'],
            [['order_datetime', 'order_payment_type', 'order_hash'], 'safe'],
            [['order_total', 'order_discount'], 'safe'],
            [['sysuser_fullname', 'pos.pos_title'], 'safe'],
        ];
    }

    public function attributes() {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), ['sysuser.sysuser_fullname', 'pos.pos_title']);
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params) {

        //var_dump($params);
        $query = Order::find();
        $query->joinWith(['sysuser']);
        $query->joinWith(['pos']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['sysuser_fullname'] = [
            'asc' => ['sysuser_fullname' => SORT_ASC],
            'desc' => ['sysuser_fullname' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['pos.pos_title'] = [
            'asc' => ['pos.pos_title' => SORT_ASC],
            'desc' => ['pos.pos_title' => SORT_DESC],
        ];

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'order_id' => $this->order_id,
            'pos_id' => $this->pos_id,
            'seller_id' => $this->seller_id,
            'sysuser_id' => $this->sysuser_id,
                //'order_datetime' => $this->order_datetime,
                //'order_day_sequence_number' => $this->order_day_sequence_number,
                //'order_total' => $this->order_total,
                //'order_discount' => $this->order_discount,
        ]);


        $orderSearch = Yii::$app->request->get('OrderSearch');

        if (isset($orderSearch['order_datetime_min']) && strlen($orderSearch['order_datetime_min']) > 0) {
            $timestamp = strtotime($orderSearch['order_datetime_min']);
            if ($timestamp !== false) {
                $min_date = date('Y-m-d 00:00:00', $timestamp);
                $query->andFilterWhere(['>=', 'order_datetime', $min_date]);
            }
        }
        if (isset($orderSearch['order_datetime_max']) && strlen($orderSearch['order_datetime_max']) > 0) {
            $timestamp = strtotime($orderSearch['order_datetime_max']);
            if ($timestamp !== false) {
                $max_date = date('Y-m-d 23:59:59', $timestamp);
                $query->andFilterWhere(['<=', 'order_datetime', $max_date]);
            }
        }
        if (isset($orderSearch['order_total_min']) && strlen($orderSearch['order_total_min']) > 0) {
            $value = filter_var($orderSearch['order_total_min'], FILTER_VALIDATE_FLOAT);
            if ($value) {
                $query->andFilterWhere(['>=', 'order_total', $value]);
            }
        }
        if (isset($orderSearch['order_total_max']) && strlen($orderSearch['order_total_max']) > 0) {
            $value = filter_var($orderSearch['order_total_max'], FILTER_VALIDATE_FLOAT);
            if ($value) {
                $query->andFilterWhere(['<=', 'order_total', $value]);
            }
        }


        $query->andFilterWhere(['like', 'order_payment_type', $this->order_payment_type]);

        //$query->andFilterWhere(['like', 'sysuser.sysuser_fullname', $this->getAttribute('sysuser.sysuser_fullname')]);
        $query->andFilterWhere(['like', 'sysuser_fullname', $this->getAttribute('sysuser.sysuser_fullname')]);
        $query->andFilterWhere(['like', 'pos.pos_title', $this->getAttribute('pos.pos_title')]);

        return $dataProvider;
    }

    public function getOrderTotal() {

        $orderSearch = Yii::$app->request->get('OrderSearch');
        
        $where=[];
        
        if (isset($orderSearch['order_datetime_min']) && strlen($orderSearch['order_datetime_min']) > 0) {
            $timestamp = strtotime($orderSearch['order_datetime_min']);
            if ($timestamp !== false) {
                $min_date = date('Y-m-d 00:00:00', $timestamp);
                $where[]=" (order_datetime>='$min_date') ";
            }
        }
        if (isset($orderSearch['order_datetime_max']) && strlen($orderSearch['order_datetime_max']) > 0) {
            $timestamp = strtotime($orderSearch['order_datetime_max']);
            if ($timestamp !== false) {
                $max_date = date('Y-m-d 23:59:59', $timestamp);
                $where[]=" (order_datetime<='$max_date') ";
            }
        }
        if (isset($orderSearch['order_total_min']) && strlen($orderSearch['order_total_min']) > 0) {
            $value = filter_var($orderSearch['order_total_min'], FILTER_VALIDATE_FLOAT);
            if ($value) {
                $where[]=" (order_total>=$value) ";
            }
        }
        if (isset($orderSearch['order_total_max']) && strlen($orderSearch['order_total_max']) > 0) {
            $value = filter_var($orderSearch['order_total_max'], FILTER_VALIDATE_FLOAT);
            if ($value) {
                $where[]=" (order_total<=$value) ";
            }
        }

        if(strlen($orderSearch['order_payment_type'])>0){
            $value=preg_replace("/\\W/","",$orderSearch['order_payment_type']);
            $where[]=" (order_payment_type='$value') ";
        }

        
        if(strlen($orderSearch['sysuser.sysuser_fullname'])>0){
            $value=  Yii::$app->db->quoteValue($orderSearch['sysuser.sysuser_fullname']);
            $where[]=" locate($value,sysuser.sysuser_fullname) ";
        }

        if(strlen($orderSearch['pos.pos_title'])>0){
            $value=  Yii::$app->db->quoteValue($orderSearch['pos.pos_title']);
            $where[]=" locate($value,pos.pos_title) ";
        }

        //query database
        $sql='
            SELECT SUM(order_total) as order_total_sum, count(order_id) as order_num, AVG(if(order_total>0,order_total,0)) as order_total_avg
            FROM `order`
                  inner join pos on `order`.pos_id=pos.pos_id
                  inner join sysuser on `order`.sysuser_id=sysuser.sysuser_id
            ';
        if(count($where)>0){
            $sql.=' where '. join (' AND ',$where);
        };        
        $command = Yii::$app->db->createCommand($sql);


        $summa = $command->queryOne();
        return $summa;
    }

}
