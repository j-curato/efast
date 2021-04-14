<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DvAucsEntries;

/**
 * DvAucsEntriesSearch represents the model behind the search form of `app\models\DvAucsEntries`.
 */
class DvAucsEntriesSearch extends DvAucsEntries
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'dv_aucs_id', 'raoud_id'], 'integer'],
            [['amount_disbursed', 'vat_nonvat', 'ewt_goods_services', 'compensation', 'other_trust_liabilities', 'total_withheld'], 'number'],
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
        $query = DvAucsEntries::find();

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
        $query->joinWith("dvAucs");
        // grid filtering conditions
        // $this->id=$dv_id;
        $query->andFilterWhere([
            'dv_aucs_entries.id' => $this->id,
            // 'dv_aucs_id' => $this->dv_aucs_id,
            'raoud_id' => $this->raoud_id,
            'amount_disbursed' => $this->amount_disbursed,
            'vat_nonvat' => $this->vat_nonvat,
            'ewt_goods_services' => $this->ewt_goods_services,
            'compensation' => $this->compensation,
            'other_trust_liabilities' => $this->other_trust_liabilities,
            'total_withheld' => $this->total_withheld,
        ]);
        $query->andFilterWhere(['like', 'dv_aucs.dv_number', $this->dv_aucs_id]);
        return $dataProvider;
    }
}
