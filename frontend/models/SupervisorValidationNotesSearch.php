<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SupervisorValidationNotes;

/**
 * SupervisorValidationNotesSearch represents the model behind the search form of `app\models\SupervisorValidationNotes`.
 */
class SupervisorValidationNotesSearch extends SupervisorValidationNotes
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'passion', 'integrety', 'competence', 'creativity', 'synergy', 'love_of_country', 'int_gbl_olk', 'del_solution', 'net_link', 'del_exl_res', 'collaborating', 'agility', 'proflsm_int'], 'integer'],
            [['employee_name', 'evaluation_period', 'ttl_suc_msr', 'valid_msr', 'accomplishments', 'pgs_rating', 'comment', 'dev_intervention', 'created_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = SupervisorValidationNotes::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'passion' => $this->passion,
            'integrety' => $this->integrety,
            'competence' => $this->competence,
            'creativity' => $this->creativity,
            'synergy' => $this->synergy,
            'love_of_country' => $this->love_of_country,
            'int_gbl_olk' => $this->int_gbl_olk,
            'del_solution' => $this->del_solution,
            'net_link' => $this->net_link,
            'del_exl_res' => $this->del_exl_res,
            'collaborating' => $this->collaborating,
            'agility' => $this->agility,
            'proflsm_int' => $this->proflsm_int,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'employee_name', $this->employee_name])
            ->andFilterWhere(['like', 'evaluation_period', $this->evaluation_period])
            ->andFilterWhere(['like', 'ttl_suc_msr', $this->ttl_suc_msr])
            ->andFilterWhere(['like', 'valid_msr', $this->valid_msr])
            ->andFilterWhere(['like', 'accomplishments', $this->accomplishments])
            ->andFilterWhere(['like', 'pgs_rating', $this->pgs_rating])
            ->andFilterWhere(['like', 'comment', $this->comment])
            ->andFilterWhere(['like', 'dev_intervention', $this->dev_intervention]);

        return $dataProvider;
    }
}
