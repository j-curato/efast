<?php

namespace app\models;

use Yii;
use yii\base\Model;
use common\models\User;
use app\models\PoTransmittal;
use yii\data\ActiveDataProvider;

/**
 * PoTransmittalSearch represents the model behind the search form of `app\models\PoTransmittal`.
 */
class PoTransmittalSearch extends PoTransmittal
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['transmittal_number', 'date', 'created_at', 'status'], 'safe'],
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

        $query = PoTransmittal::find();
        if (!Yii::$app->user->can('ro_accounting_admin')) {
            $user_data = User::getUserDetails();
            $query->andWhere('fk_office_id = :office_id', ['office_id' => $user_data->employee->office->id]);
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
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'transmittal_number', $this->transmittal_number])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'date', $this->date]);

        return $dataProvider;
    }
}
