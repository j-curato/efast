<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Payee;
use Yii;

/**
 * PayeeSearch represents the model behind the search form of `app\models\Payee`.
 */
class PayeeSearch extends Payee
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['account_name', 'registered_name', 'contact_person', 'registered_address', 'contact', 'remark', 'tin_number', 'account_num', 'fk_bank_id', 'fk_office_id','created_at'], 'safe'],
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
        $query = Payee::find()
            ->andWhere('isEnable = 1');

        // add conditions that should always apply here
        if (!Yii::$app->user->can('ro_procurement_admin')) {
            $query->andWhere('fk_office_id = :office_id', ['office_id' => YIi::$app->user->identity->fk_office_id]);
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
        $query->joinWith('bank');

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'account_name', $this->account_name])
            ->andFilterWhere(['like', 'registered_name', $this->registered_name])
            ->andFilterWhere(['like', 'contact_person', $this->contact_person])
            ->andFilterWhere(['like', 'registered_address', $this->registered_address])
            ->andFilterWhere(['like', 'contact', $this->contact])
            ->andFilterWhere(['like', 'remark', $this->remark])
            ->andFilterWhere(['like', 'account_num', $this->account_num])
            ->andFilterWhere(['like', 'banks.name', $this->fk_bank_id])
            ->andFilterWhere(['like', 'fk_office_id', $this->fk_office_id])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['like', 'tin_number', $this->tin_number]);

        return $dataProvider;
    }
}
