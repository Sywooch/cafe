<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Packaging;

/**
 * PackagingSearch represents the model behind the search form about `app\models\Packaging`.
 */
class PackagingSearch extends Packaging
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['packaging_id'], 'integer'],
            [['packaging_icon', 'packaging_title'], 'safe'],
            [['packaging_price'], 'number'],
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
        $query = Packaging::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'packaging_id' => $this->packaging_id,
            'packaging_price' => $this->packaging_price,
        ]);

        $query->andFilterWhere(['like', 'packaging_icon', $this->packaging_icon])
            ->andFilterWhere(['like', 'packaging_title', $this->packaging_title]);

        return $dataProvider;
    }
}
