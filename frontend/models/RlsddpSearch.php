<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Rlsddp;

/**
 * RlsddpSearch represents the model behind the search form of `app\models\Rlsddp`.
 */
class RlsddpSearch extends Rlsddp
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'fk_acctbl_offr', 'is_blottered', 'fk_property_status_id', 'fk_supvr'], 'integer'],
            [['serial_number', 'date', 'police_station', 'circumstances', 'created_at'], 'safe'],
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
        $query = Rlsddp::find();

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
            'date' => $this->date,
            'fk_acctbl_offr' => $this->fk_acctbl_offr,
            'is_blottered' => $this->is_blottered,
            'fk_property_status_id' => $this->fk_property_status_id,
            'fk_supvr' => $this->fk_supvr,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'serial_number', $this->serial_number])
            ->andFilterWhere(['like', 'police_station', $this->police_station])
            ->andFilterWhere(['like', 'circumstances', $this->circumstances]);

        return $dataProvider;
    }
}
