<?php

namespace app\models;

use Yii;
use yii\base\Model;
use common\models\User;
use app\models\IarIndex;
use yii\data\ActiveDataProvider;

/**
 * IarIndexSearch represents the model behind the search form of `app\models\IarIndex`.
 */
class IarIndexSearch extends IarIndex
{
    public $rfi_number;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [[
                'iar_number',
                'ir_number',
                'rfi_number',
                'office_name',
                'division',
                'inspector_name',
                'requested_by_name',
                'end_user',
                'po_number',
                'payee_name',
                'purpose',
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
        $query = IarIndex::find();

        if (!yii::$app->user->can('ro_inspection_admin')) {
            $user_data = User::getUserDetails();
            $query->andWhere('iar_index.office_name = :office', ['office' => $user_data->employee->office->office_name]);
            if (!Yii::$app->user->can('ro_inspection_admin') && !Yii::$app->user->can('po_inspection_admin')) {
                $query->andWhere('iar_index.division = :division', ['division' => $user_data->employee->empDivision->division ?? '']);
            }
        }

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
        ]);

        $query->andFilterWhere(['like', 'iar_number', $this->iar_number])
            ->andFilterWhere(['like', 'ir_number', $this->ir_number])
            ->andFilterWhere(['like', 'rfi_number', $this->rfi_number])
            ->andFilterWhere(['like', 'office_name', $this->office_name])
            ->andFilterWhere(['like', 'division', $this->division])
            ->andFilterWhere(['like', 'inspector_name', $this->inspector_name])
            ->andFilterWhere(['like', 'requested_by_name', $this->requested_by_name])
            ->andFilterWhere(['like', 'end_user', $this->end_user])
            ->andFilterWhere(['like', 'po_number', $this->po_number])
            ->andFilterWhere(['like', 'purpose', $this->purpose])
            ->andFilterWhere(['like', 'payee_name', $this->payee_name]);

        $query->orderBy('iar_number');
        return $dataProvider;
    }
}
