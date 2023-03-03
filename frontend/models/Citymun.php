<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "citymun".
 *
 * @property int $id
 * @property string $city_mun
 *
 * @property SsfSpNum[] $ssfSpNums
 */
class Citymun extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'citymun';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['city_mun', 'fk_office_id'], 'required'],
            [['fk_office_id'], 'integer'],
            [['city_mun'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'city_mun' => 'City Mun',
            'fk_office_id' => 'Office',
        ];
    }

    /**
     * Gets query for [[SsfSpNums]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSsfSpNums()
    {
        return $this->hasMany(SsfSpNum::class, ['fk_citymun_id' => 'id']);
    }
}
