<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\BacComposition;
use Yii;

/**
 * BacCompositionnSearch represents the model behind the search form of `app\models\BacComposition`.
 */
class BacCompositionnSearch extends BacComposition
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['effectivity_date', 'expiration_date', 'rso_number'], 'safe'],
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
        $query = BacComposition::find();

        // add conditions that should always apply here
        if (!Yii::$app->user->can('ro_procurement_admin')) {
            $query->andWhere('fk_office_id = :office_id', ['office_id' => Yii::$app->user->identity->fk_office_id]);
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
            'effectivity_date' => $this->effectivity_date,
            'expiration_date' => $this->expiration_date,
        ]);

        $query->andFilterWhere(['like', 'rso_number', $this->rso_number]);

        return $dataProvider;
    }
}
