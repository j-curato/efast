<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ProcessOrsNewView;

/**
 * ProcessOrsNewViewSearch represents the model behind the search form of `app\models\ProcessOrsNewView`.
 */
class ProcessOrsNewViewSearch extends ProcessOrsNewView
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id',], 'integer'],
            [[
                'serial_number',
                'tracking_number',
                'payee',
                'particular',
                'allotment_uacs',
                'allotment_account_title',
                'ors_uacs',
                'ors_account_tiitle',
                'is_cancelled',

            ], 'safe'],
            [['amount'], 'number'],
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
        $query = ProcessOrsNewView::find();

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
            'id' => $this->id
        ]);

        $query->andFilterWhere(['like', 'serial_number', $this->serial_number])
            ->andFilterWhere(['like', 'tracking_number', $this->tracking_number])
            ->andFilterWhere(['like', 'payee', $this->payee])
            ->andFilterWhere(['like', 'particular', $this->particular])
            ->andFilterWhere(['like', 'allotment_uacs', $this->allotment_uacs])
            ->andFilterWhere(['like', 'allotment_account_title', $this->allotment_account_title])
            ->andFilterWhere(['like', 'ors_uacs', $this->ors_uacs])
            ->andFilterWhere(['like', 'ors_account_tiitle', $this->ors_account_tiitle])
            ->andFilterWhere(['like', 'is_cancelled', $this->is_cancelled])
            ->andFilterWhere(['like', 'amount', $this->amount]);

        return $dataProvider;
    }
}
