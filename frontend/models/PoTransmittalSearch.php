<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PoTransmittal;
use Yii;

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
        if (!Yii::$app->user->can('super-user')) {
            $user_data = Yii::$app->memem->getUserData();
            $query->andWhere('fk_office_id = :office_id', ['office_id' => $user_data->office->id]);
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
