<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Discount;

/**
 * DiscountSearch represents the model behind the search form about `app\models\Discount`.
 */
class DiscountSearch extends Discount
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['discount_id'], 'integer'],
            [['discount_title', 'discount_description', 'discount_rule'], 'safe'],
        ];
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
        $query = Discount::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'discount_id' => $this->discount_id,
        ]);

        $query->andFilterWhere(['like', 'discount_title', $this->discount_title])
            ->andFilterWhere(['like', 'discount_description', $this->discount_description])
            ->andFilterWhere(['like', 'discount_rule', $this->discount_rule]);

        return $dataProvider;
    }
}
