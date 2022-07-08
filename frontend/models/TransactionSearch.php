<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Transaction;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * TransactionSearch represents the model behind the search form of `app\models\Transaction`.
 */
class TransactionSearch extends Transaction
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id',], 'integer'],
            [['responsibility_center_id', 'particular', 'tracking_number', 'earmark_no', 'payroll_number', 'transaction_date', 'transaction_time', 'payee_id'], 'safe'],
            [['gross_amount'], 'number'],

            [[
                'responsibility_center_id',
                'particular',
                'tracking_number',
                'earmark_no',
                'payroll_number',
                'transaction_date',
                'transaction_time',
                'payee_id',
                'gross_amount',
            ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
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
        $province = Yii::$app->user->identity->division;
        $q = Yii::$app->db->createCommand("SELECT tracking_number 
        FROM transaction_totals 
        WHERE 
         total_dv <total_ors ")->queryAll();

        $qwe = [];
        $sql = Yii::$app->db->getQueryBuilder()->buildCondition(['IN', 'transaction.tracking_number', ArrayHelper::getColumn($q, 'tracking_number')], $qwe);
        $q = Transaction::find();
        // ->where("$sql",$qwe)

        if (
            !Yii::$app->user->can('super-user')

        ) {
            $q->joinWith('responsibilityCenter')
                ->where('responsibility_center.name LIKE :province', ['province' => $province]);
        }
        $query = $q->orderBy("id DESC");;
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
        $query->joinWith('responsibilityCenter');
        // grid filtering conditions
        $query->andFilterWhere([
            'transaction.id' => $this->id,
            'gross_amount' => $this->gross_amount,
        ]);

        $query->andFilterWhere(['like', 'particular', $this->particular])

            ->andFilterWhere(['like', 'payee.account_name', $this->payee_id])
            ->andFilterWhere(['like', 'tracking_number', $this->tracking_number])
            ->andFilterWhere(['like', 'earmark_no', $this->earmark_no])
            ->andFilterWhere(['like', 'payroll_number', $this->payroll_number])
            ->andFilterWhere(['like', 'transaction_date', $this->transaction_date])
            ->andFilterWhere(['like', 'responsibility_center.name', $this->responsibility_center_id])
            ->andFilterWhere(['like', 'transaction_time', $this->transaction_time]);

        return $dataProvider;
    }
}
