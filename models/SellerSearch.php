<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Seller;

/**
 * SellerSearch represents the model behind the search form about `app\models\Seller`.
 */
class SellerSearch extends Seller {
    /* your calculated attribute */

    //public $sysuser_fullname;

    public function attributes() {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), ['sysuser.sysuser_fullname','pos.pos_title']);
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['seller_id', 'sysuser_id', 'pos_id'], 'integer'],
            [['seller_salary', 'seller_commission_fee'], 'number'],
            [['sysuser.sysuser_fullname','pos.pos_title'], 'safe'],
        ];
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
        $query = Seller::find();

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
            $query->joinWith(['sysuser']);
            $query->joinWith(['pos']);
            return $dataProvider;
        }

        $query->andFilterWhere([
            'seller_id' => $this->seller_id,
            'sysuser_id' => $this->sysuser_id,
            'pos_id' => $this->pos_id,
            'seller_salary' => $this->seller_salary,
            'seller_commission_fee' => $this->seller_commission_fee,
                //
        ]);
        $query->andFilterWhere(['like', 'sysuser.sysuser_fullname', $this->getAttribute('sysuser.sysuser_fullname')]);
        $query->andFilterWhere(['like', 'pos.pos_title', $this->getAttribute('pos.pos_title')]);
        // filter by sysuser name
        $query->joinWith(['sysuser']);
        $query->joinWith(['pos']);

        return $dataProvider;
    }

}
