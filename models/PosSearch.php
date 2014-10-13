<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Pos;

/**
 * PosSearch represents the model behind the search form about `app\models\Pos`.
 */
class PosSearch extends Pos
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pos_id'], 'integer'],
            [['pos_title', 'pos_address', 'pos_timetable'], 'safe'],
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
        $query = Pos::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'pos_id' => $this->pos_id,
        ]);

        $query->andFilterWhere(['like', 'pos_title', $this->pos_title])
            ->andFilterWhere(['like', 'pos_address', $this->pos_address])
            ->andFilterWhere(['like', 'pos_timetable', $this->pos_timetable]);

        return $dataProvider;
    }
}
