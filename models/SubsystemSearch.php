<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Subsystem;

/**
 * SubsystemSearch represents the model behind the search form about `app\models\Subsystem`.
 */
class SubsystemSearch extends Subsystem
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['subsystemId'], 'integer'],
            [['subsystemTitle', 'subsystemUrl', 'subsystemApiKey'], 'safe'],
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
        $query = Subsystem::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if ($this->load($params) && !$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'subsystemId' => $this->subsystemId,
        ]);

        $query->andFilterWhere(['like', 'subsystemTitle', $this->subsystemTitle])
            ->andFilterWhere(['like', 'subsystemUrl', $this->subsystemUrl])
            ->andFilterWhere(['like', 'subsystemApiKey', $this->subsystemApiKey]);

        return $dataProvider;
    }
}
