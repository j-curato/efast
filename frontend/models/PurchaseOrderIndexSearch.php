<?php

namespace app\models;

use Yii;
use yii\base\Model;
use common\models\User;
use yii\data\ActiveDataProvider;
use app\models\PurchaseOrderIndex;

/**
 * PurchaseOrderIndexSearch represents the model behind the search form of `app\models\PurchaseOrderIndex`.
 */
class PurchaseOrderIndexSearch extends PurchaseOrderIndex
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [[
                'po_number',
                'purpose',
                'division',
                'office_name',
                'is_cancelled',
                'mode_of_procurement_name',
                'created_at',
                'payee_name',

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
        $query = PurchaseOrderIndex::find();
        if (!yii::$app->user->can('ro_procurement_admin')) {
            $user_data = User::getUserDetails();
            $query->andWhere('office_name = :office', ['office' => $user_data->employee->office->office_name]);
        }
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want employee_to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([]);

        $query->andFilterWhere(['like', 'po_number', $this->po_number])
            ->andFilterWhere(['like', 'purpose', $this->purpose])
            ->andFilterWhere(['like', 'division', $this->division])
            ->andFilterWhere(['like', 'office_name', $this->office_name])
            ->andFilterWhere(['like', 'is_cancelled', $this->is_cancelled])
            ->andFilterWhere(['like', 'mode_of_procurement_name', $this->mode_of_procurement_name])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['like', 'payee_name', $this->payee_name]);


        return $dataProvider;
    }
}
