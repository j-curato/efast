<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Liquidation;
use Yii;

/**
 * LiquidationSearch represents the model behind the search form of `app\models\Liquidation`.
 */
class LiquidationSearch extends Liquidation
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'payee_id', 'responsibility_center_id', 'is_cancelled', 'po_transaction_id', 'check_range_id', 'is_locked'], 'integer'],
            [['check_date', 'check_number', 'dv_number', 'particular', 'reporting_period', 'created_at', 'status'], 'safe'],
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
        $province = Yii::$app->user->identity->province;
        $q = Liquidation::find();
        if (
            $province === 'adn' ||
            $province === 'sdn' ||
            $province === 'sds' ||
            $province === 'sdn' ||
            $province === 'pdi'
        ) {
            $q->where('province LIKE :province', ['province' => $province]);
        }
        $query = $q;

        $query = Liquidation::find();

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
            'payee_id' => $this->payee_id,
            'responsibility_center_id' => $this->responsibility_center_id,
            'is_cancelled' => $this->is_cancelled,
            'created_at' => $this->created_at,
            'po_transaction_id' => $this->po_transaction_id,
            'check_range_id' => $this->check_range_id,
            'is_locked' => $this->is_locked,
        ]);

        $query->andFilterWhere(['like', 'check_date', $this->check_date])
            ->andFilterWhere(['like', 'check_number', $this->check_number])
            ->andFilterWhere(['like', 'dv_number', $this->dv_number])
            ->andFilterWhere(['like', 'particular', $this->particular])
            ->andFilterWhere(['like', 'reporting_period', $this->reporting_period])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
