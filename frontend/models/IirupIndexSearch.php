<?php

namespace app\models;

use Yii;
use yii\base\Model;
use common\models\User;
use app\models\IirupIndex;
use yii\data\ActiveDataProvider;

/**
 * IirupIndexSearch represents the model behind the search form of `app\models\IirupIndex`.
 */
class IirupIndexSearch extends IirupIndex
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['serial_number', 'office_name', 'approved_by', 'accountable_officer'], 'safe'],
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
        $query = IirupIndex::find();

        // add conditions that should always apply here
        if (!Yii::$app->user->can('ro_property_admin')) {
            $user_data = User::getUserDetails();
            $query->andWhere('office_name = :office_name', ['office_name' => $user_data->employee->office->office_name]);
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

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,

        ]);
        $query->andFilterWhere(['like', 'office_name', $this->office_name]);
        $query->andFilterWhere(['like', 'approved_by', $this->approved_by]);
        $query->andFilterWhere(['like', 'accountable_officer', $this->accountable_officer]);
        return $dataProvider;
    }
}
