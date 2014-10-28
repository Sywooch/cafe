<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Order;

/**
 * OrderSearch represents the model behind the search form about `app\models\Order`.
 */
class OrderSearch extends Order
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'pos_id', 'seller_id', 'sysuser_id', 'order_day_sequence_number'], 'integer'],
            [['order_datetime', 'order_payment_type', 'order_hash'], 'safe'],
            [['order_total', 'order_discount'], 'safe'],
            [['sysuser.sysuser_fullname','pos.pos_title'], 'safe'],
        ];
    }
    public function attributes() {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), ['sysuser.sysuser_fullname','pos.pos_title']);
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
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
    public function search($params)
    {
        $query = Order::find();
        $query->joinWith(['sysuser']);
        $query->joinWith(['pos']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['sysuser.sysuser_fullname'] = [
            'asc' => ['sysuser.sysuser_fullname' => SORT_ASC,'pos.pos_title'=>SORT_ASC],
            'desc' => ['sysuser.sysuser_fullname' => SORT_DESC,'pos.pos_title'=>SORT_DESC],
        ];
        $dataProvider->sort->attributes['pos.pos_title'] = [
            'asc' => ['pos.pos_title' => SORT_ASC],
            'desc' => ['pos.pos_title'=>SORT_DESC],
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
            'order_day_sequence_number' => $this->order_day_sequence_number,
            //'order_total' => $this->order_total,
            //'order_discount' => $this->order_discount,
        ]);
        
        
        $timestamp = strtotime($this->order_datetime);
        if($timestamp!==false){
            $min_date=date('Y-m-d 00:00:00',$timestamp);
            $query->andFilterWhere(['>=', 'order_datetime', $min_date]);
            
            $max_date=date('Y-m-d 23:59:59',$timestamp);
            $query->andFilterWhere(['<=', 'order_datetime', $max_date]);
        }
        
        
        
        //'order_total' => $this->order_total,
        $order_total=preg_split("/\\.{2,}/",$this->order_total);
        $n_params=count($order_total);
        if($n_params==2){
            $minvalue=filter_var ( $order_total[0], FILTER_VALIDATE_FLOAT);
            if($minvalue){
                $query->andFilterWhere(['>=', 'order_total', $minvalue]);
            }
            
            $maxvalue=filter_var ( $order_total[1], FILTER_VALIDATE_FLOAT);
            if($maxvalue){
                $query->andFilterWhere(['<=', 'order_total', $maxvalue]);
            }
        }elseif($exactvalue=filter_var ( $this->order_total, FILTER_VALIDATE_FLOAT)){
            $query->andFilterWhere(['=', 'order_total', $exactvalue]);
        }

        
        
        //'order_discount' => $this->order_discount,
        $order_discount=preg_split("/\\.{2,}/",$this->order_discount);
        $n_params=count($order_discount);
        if($n_params==2){
            $minvalue=filter_var ( $order_discount[0], FILTER_VALIDATE_FLOAT);
            if($minvalue){
                $query->andFilterWhere(['>=', 'order_discount', $minvalue]);
            }
            
            $maxvalue=filter_var ( $order_discount[1], FILTER_VALIDATE_FLOAT);
            if($maxvalue){
                $query->andFilterWhere(['<=', 'order_discount', $maxvalue]);
            }
        }elseif($exactvalue=filter_var ( $this->order_discount, FILTER_VALIDATE_FLOAT)){
            $query->andFilterWhere(['=', 'order_discount', $exactvalue]);
        }
        
        
        
        $query->andFilterWhere(['like', 'order_payment_type', $this->order_payment_type])
            ->andFilterWhere(['like', 'order_hash', $this->order_hash]);

        $query->andFilterWhere(['like', 'sysuser.sysuser_fullname', $this->getAttribute('sysuser.sysuser_fullname')]);
        $query->andFilterWhere(['like', 'pos.pos_title', $this->getAttribute('pos.pos_title')]);

        return $dataProvider;
    }
}
