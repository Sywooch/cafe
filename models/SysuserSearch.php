<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sysuser;

/**
 * SysuserSearch represents the model behind the search form about `app\models\Sysuser`.
 */
class SysuserSearch extends Sysuser
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sysuser_id'], 'integer'],
            [['sysuser_fullname', 'sysuser_login', 'sysuser_password', 'sysuser_telephone', 'sysuser_token', 'sysuser_role'], 'safe'],
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
        $query = Sysuser::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'sysuser_id' => $this->sysuser_id,
            'sysuser_role' => $this->sysuser_role,
        ]);

        $query->andFilterWhere(['like', 'sysuser_fullname', $this->sysuser_fullname])
            ->andFilterWhere(['like', 'sysuser_login', $this->sysuser_login])
            ->andFilterWhere(['like', 'sysuser_password', $this->sysuser_password])
            ->andFilterWhere(['like', 'sysuser_telephone', $this->sysuser_telephone])
            ->andFilterWhere(['like', 'sysuser_token', $this->sysuser_token]);

        return $dataProvider;
    }
}
