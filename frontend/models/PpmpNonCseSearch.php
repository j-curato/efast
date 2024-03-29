<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PpmpNonCse;
use Yii;

/**
 * PpmpNonCseSearch represents the model behind the search form of `app\models\PpmpNonCse`.
 */
class PpmpNonCseSearch extends PpmpNonCse
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['created_at'], 'safe'],
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
        $query = PpmpNonCse::find();
        if (!Yii::$app->user->can('super-user')) {
            $user_province = '';
            if ($user_province === 'ro') {
                $query->andWhere("responsible_center  = :r_center", ['r_center' => Yii::$app->user->identity->division]);
            } else {
                $query->andWhere("responsible_center  = :r_center", ['r_center' => $user_province]);
            }
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

            'created_at' => $this->created_at,
        ]);

        // $query->andFilterWhere(['like', 'project_name', $this->project_name]);

        return $dataProvider;
    }
}
