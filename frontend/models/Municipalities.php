<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "municipalities".
 *
 * @property int $id
 * @property int $fk_province_id
 * @property string $municipality_name
 *
 * @property Barangays[] $barangays
 * @property Mgrfrs[] $mgrfrs
 * @property Provinces $fkProvince
 */
class Municipalities extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'municipalities';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_province_id', 'municipality_name'], 'required'],
            [['fk_province_id'], 'integer'],
            [['municipality_name'], 'string', 'max' => 255],
            [['fk_province_id'], 'exist', 'skipOnError' => true, 'targetClass' => Provinces::class, 'targetAttribute' => ['fk_province_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_province_id' => 'Fk Province ID',
            'municipality_name' => 'Municipality Name',
        ];
    }

    /**
     * Gets query for [[Barangays]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBarangays()
    {
        return $this->hasMany(Barangays::class, ['fk_municipality_id' => 'id']);
    }

    /**
     * Gets query for [[Mgrfrs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMgrfrs()
    {
        return $this->hasMany(Mgrfrs::class, ['fk_municipality_id' => 'id']);
    }

    /**
     * Gets query for [[FkProvince]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkProvince()
    {
        return $this->hasOne(Provinces::class, ['id' => 'fk_province_id']);
    }
    public static function getMunicipalitiesByProvinceId($id)
    {
        return Municipalities::find()->where('fk_province_id = :id', ['id' => $id])->asArray()->all();
    }
}
