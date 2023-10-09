<?php

namespace app\models;

use Yii;
use yii\base\Model;
use common\models\User;
use app\models\Location;
use yii\data\ActiveDataProvider;

/**
 * LocationSearch represents the model behind the search form of `app\models\Location`.
 */
class LocationSearch extends Location
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'is_nc', 'fk_division_id'], 'integer'],
            [['location', 'created_at'], 'safe'],
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
        $query = Location::find();

        // add conditions that should always apply here
        if (!Yii::$app->user->can('ro_property_admin')) {
            $user_data = User::getUserDetails();
            $office_id = $user_data->employee->office->id;
            $query->where('fk_office_id = :office_id', ['office_id' => $office_id]);
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
            'is_nc' => $this->is_nc,
            'fk_division_id' => $this->fk_division_id,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'location', $this->location]);

        return $dataProvider;
    }
}
