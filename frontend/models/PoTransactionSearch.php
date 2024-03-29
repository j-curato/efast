<?php

namespace app\models;

use Yii;
use yii\base\Model;
use common\models\User;
use app\models\PoTransaction;
use yii\data\ActiveDataProvider;

/**
 * PoTransactionSearch represents the model behind the search form of `app\models\PoTransaction`.
 */
class PoTransactionSearch extends PoTransaction
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'po_responsibility_center_id'], 'integer'],
            [['payee', 'particular', 'payroll_number', 'tracking_number'], 'safe'],
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



        $q = PoTransaction::find();
        if (!Yii::$app->user->can('ro_accounting_admin')) {
            $user_data = User::getUserDetails();
            $province = strtolower($user_data->employee->office->office_name);
            $q->where('tracking_number LIKE :province', ['province' => "$province%"]);
        }
        $query = $q;
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
            'po_responsibility_center_id' => $this->po_responsibility_center_id,
            'amount' => $this->amount,
        ]);

        $query->andFilterWhere(['like', 'payee', $this->payee])
            ->andFilterWhere(['like', 'particular', $this->particular])
            ->andFilterWhere(['like', 'tracking_number', $this->tracking_number])
            ->andFilterWhere(['like', 'payroll_number', $this->payroll_number]);

        return $dataProvider;
    }
}
