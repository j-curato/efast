<?php

namespace app\models;

use Yii;
use yii\base\Model;
use common\models\User;
use yii\data\ActiveDataProvider;
use app\models\VwPoTransmittalIndex;

/**
 * VwPoTransmittalIndexSearch represents the model behind the search form of `app\models\VwPoTransmittalIndex`.
 */
class VwPoTransmittalIndexSearch extends VwPoTransmittalIndex
{
    public $bookFilter;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],

            [[
                'transmittal_number',
                'date',
                'is_accepted',
                'status',
                'fk_office_id',
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
        $query = VwPoTransmittalIndex::find();
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
            'id' => $this->id,

        ]);

        $query->andFilterWhere(['like', 'transmittal_number', $this->transmittal_number])
            ->andFilterWhere(['like', 'date', $this->date])
            ->andFilterWhere(['like', 'status', $this->status]);


        return $dataProvider;
    }
}
