<?php

namespace app\models;

use Yii;
use app\models\Cdr;
use yii\base\Model;
use common\models\User;
use yii\data\ActiveDataProvider;

/**
 * CdrSearch represents the model behind the search form of `app\models\Cdr`.
 */
class CdrSearch extends Cdr
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'is_final'], 'integer'],
            [['serial_number', 'reporting_period', 'province', 'book_name', 'report_type'], 'safe'],
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
        $query = Cdr::find();
        if (!Yii::$app->user->can('ro_accounting_admin')) {
            $user_data = User::getUserDetails();
            $query->where('cdr.province = :province', ['province' =>  $user_data->employee->office->office_name]);
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
        $query->joinWith('book');
        $query->andFilterWhere([
            'cdr.id' => $this->id,
            'is_final' => $this->is_final,
        ]);

        $query->andFilterWhere(['like', 'serial_number', $this->serial_number])
            ->andFilterWhere(['like', 'reporting_period', $this->reporting_period])
            ->andFilterWhere(['like', 'province', $this->province])
            ->andFilterWhere(['like', 'books.name', $this->book_name])
            ->andFilterWhere(['like', 'report_type', $this->report_type]);

        return $dataProvider;
    }
}
