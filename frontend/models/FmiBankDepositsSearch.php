<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\FmiBankDeposits;

/**
 * FmiBankDepositsSearch represents the model behind the search form of `app\models\FmiBankDeposits`.
 */
class FmiBankDepositsSearch extends FmiBankDeposits
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'fk_fmi_bank_deposit_type_id', 'fk_fmi_subproject_id'], 'integer'],
            [['serial_number', 'deposit_date', 'reporting_period', 'created_at'], 'safe'],
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
        $query = FmiBankDeposits::find();

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
            'deposit_date' => $this->deposit_date,
            'fk_fmi_bank_deposit_type_id' => $this->fk_fmi_bank_deposit_type_id,
            'fk_fmi_subproject_id' => $this->fk_fmi_subproject_id,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'serial_number', $this->serial_number])
            ->andFilterWhere(['like', 'reporting_period', $this->reporting_period]);

        return $dataProvider;
    }
}
