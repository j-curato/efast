<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PtrIndex;
use Yii;

/**
 * PtrIndexSearch represents the model behind the search form of `app\models\PtrIndex`.
 */
class PtrIndexSearch extends PtrIndex
{

    // ...
    // other attributes


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['date'], 'safe'],
            [['description', 'receive_by', 'article'], 'string'],
            [['ptr_number', 'office_name', 'property_number', 'par_number'], 'string', 'max' => 255],
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
        $query = PtrIndex::find();

        // add conditions that should always apply here

        if (!Yii::$app->user->can('super-user')) {
            $user_data = Yii::$app->memem->getUserData();
            $query->andWhere('office_name = :office_name', ['office_name' => $user_data->office->office_name]);
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

        $query
            ->andFilterWhere(['like', 'ptr_number', $this->ptr_number])
            ->andFilterWhere(['like', 'date', $this->date])
            ->andFilterWhere(['like', 'office_name', $this->office_name])
            ->andFilterWhere(['like', 'property_number', $this->property_number])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'receive_by', $this->receive_by])
            ->andFilterWhere(['like', 'article', $this->article])
            ->andFilterWhere(['like', 'par_number', $this->par_number]);


        return $dataProvider;
    }
}
