<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\FmiFundReleases;

/**
 * FmiFundReleasesSearch represents the model behind the search form of `app\models\FmiFundReleases`.
 */
class FmiFundReleasesSearch extends FmiFundReleases
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'fk_fmi_subproject_id', 'fk_tranche_id', 'fk_cash_disbursement_id'], 'integer'],
            [['serial_number', 'created_at'], 'safe'],
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
        $query = FmiFundReleases::find();

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
            'fk_fmi_subproject_id' => $this->fk_fmi_subproject_id,
            'fk_tranche_id' => $this->fk_tranche_id,
            'fk_cash_disbursement_id' => $this->fk_cash_disbursement_id,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'serial_number', $this->serial_number]);

        return $dataProvider;
    }
}
