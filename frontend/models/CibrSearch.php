<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Cibr;
use Yii;

/**
 * CibrSearch represents the model behind the search form of `app\models\Cibr`.
 */
class CibrSearch extends Cibr
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['reporting_period', 'province', 'book_name', 'is_final'], 'safe'],
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
        $query = Cibr::find()->joinWith('bankAccount');
        if (!Yii::$app->user->can('ro_accounting_admin')) {
            $user_data = Yii::$app->memem->getUserData();
            $query->where('bank_account.province = :province', ['province' =>  $user_data->office->office_name]);
        }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'cibr.reporting_period', $this->reporting_period])
            ->andFilterWhere(['like', 'bank_account.province', $this->province])
            ->andFilterWhere(['like', 'is_final', $this->is_final])
            ->andFilterWhere(['like', 'book_name', $this->book_name]);

        return $dataProvider;
    }
}
