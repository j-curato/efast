<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TrackingSheet;

/**
 * TrackingSheetSearch represents the model behind the search form of `app\models\TrackingSheet`.
 */
class TrackingSheetSearch extends TrackingSheet
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['tracking_number', 'particular', 'created_at', 'process_ors_id', 'payee_id'], 'safe'],
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
        $query = TrackingSheet::find();


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
        $query->joinWith('payee');
        $query->joinWith('processOrs');
        // grid filtering conditions
        $query->andFilterWhere([
            'tracking_sheet.id' => $this->id,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'tracking_number', $this->tracking_number])
            ->andFilterWhere(['like', 'particular', $this->particular])
            ->andFilterWhere(['like', 'process_ors.serial_number', $this->process_ors_id])
            ->andFilterWhere(['like', 'payee.account_name', $this->payee_id]);

        return $dataProvider;
    }
}
