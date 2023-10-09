<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\PrAoq;
use common\models\User;
use yii\data\ActiveDataProvider;

/**
 * PrAoqSearch represents the model behind the search form of `app\models\PrAoq`.
 */
class PrAoqSearch extends PrAoq
{
    public $purpose;
    public $pr_number;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [[
                'aoq_number', 'pr_date', 'created_at', 'pr_rfq_id',
                'purpose',
                'pr_number',
            ], 'safe'],
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
        $query = PrAoq::find();

        // add conditions that should always apply here
        if (!Yii::$app->user->can('ro_procurement_admin')) {
            $user_data = User::getUserDetails();
            $query->andWhere('pr_aoq.fk_office_id = :office_id', ['office_id' => $user_data->employee->office->id]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->joinWith('rfq');
        $query->joinWith('rfq.purchaseRequest');
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,

            'pr_date' => $this->pr_date,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'aoq_number', $this->aoq_number])
            ->andFilterWhere(['like', 'pr_rfq.rfq_number', $this->pr_rfq_id])
            ->andFilterWhere(['like', 'pr_purchase_request.pr_number', $this->pr_number])
            ->andFilterWhere(['like', 'pr_purchase_request.purpose', $this->purpose]);

        return $dataProvider;
    }
}
