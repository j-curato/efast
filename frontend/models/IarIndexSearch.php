<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\IarIndex;
use Yii;

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
                'responsible_center',
                'inspector_name',
                'requested_by_name',
                'end_user',
                'po_number',
                'payee_name',
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



        if (!Yii::$app->user->can('super-user')) {
            $query->where('responsible_center =:division', ['division' => Yii::$app->user->identity->division]);
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
            ->andFilterWhere(['like', 'ir_number', $this->iar_number])
            ->andFilterWhere(['like', 'rfi_number', $this->iar_number])
            ->andFilterWhere(['like', 'responsible_center', $this->iar_number])
            ->andFilterWhere(['like', 'inspector_name', $this->iar_number])
            ->andFilterWhere(['like', 'requested_by_name', $this->iar_number])
            ->andFilterWhere(['like', 'end_user', $this->iar_number])
            ->andFilterWhere(['like', 'po_number', $this->iar_number])
            ->andFilterWhere(['like', 'payee_name', $this->iar_number]);

        $query->orderBy('iar_number');
        return $dataProvider;
    }
}
