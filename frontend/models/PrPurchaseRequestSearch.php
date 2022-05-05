<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PrPurchaseRequest;
use Yii;

/**
 * PrPurchaseRequestSearch represents the model behind the search form of `app\models\PrPurchaseRequest`.
 */
class PrPurchaseRequestSearch extends PrPurchaseRequest
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['requested_by_id', 'approved_by_id', 'pr_number', 'date', 'purpose', 'created_at', 'pr_project_procurement_id', 'book_id'], 'safe'],
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
        $query = PrPurchaseRequest::find();

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
        $query->joinWith('book');
        $query->joinWith('projectProcurement');
        $query->joinWith('requestedBy');

        $user_province = strtolower(Yii::$app->user->identity->province);
        $division = strtolower(Yii::$app->user->identity->division);
        if (

            $user_province === 'ro' &&
            $division === 'sdd' ||
            $division === 'cpd' ||
            $division === 'idd' ||
            $division === 'ord'


        ) {
            $query->join('LEFT JOIN','pr_office','pr_project_procurement.pr_office_id = pr_office.id');
            $query->andWhere('pr_office.division =:division ', ['division' => $division]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'date' => $this->date,

            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'pr_number', $this->pr_number])
            ->andFilterWhere(['like', 'books.name', $this->book_id])
            ->orFilterWhere(['like', 'employee.f_name', $this->requested_by_id])
            ->orFilterWhere(['like', 'employee.l_name', $this->requested_by_id])
            ->orFilterWhere(['like', 'employee.f_name', $this->approved_by_id])
            ->orFilterWhere(['like', 'employee.l_name', $this->approved_by_id])
            ->andFilterWhere(['like', 'pr_project_procurement.title', $this->pr_project_procurement_id])
            ->andFilterWhere(['like', 'purpose', $this->purpose]);

        return $dataProvider;
    }
}
