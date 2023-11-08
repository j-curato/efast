<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "provinces".
 *
 * @property int $id
 * @property int $fk_region_id
 * @property string $province_name
 *
 * @property Mgrfrs[] $mgrfrs
 * @property Municipalities[] $municipalities
 * @property Regions $fkRegion
 */
class Provinces extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'provinces';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_region_id', 'province_name'], 'required'],
            [['fk_region_id'], 'integer'],
            [['province_name'], 'string', 'max' => 255],
            [['fk_region_id'], 'exist', 'skipOnError' => true, 'targetClass' => Regions::className(), 'targetAttribute' => ['fk_region_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_region_id' => 'Fk Region ID',
            'province_name' => 'Province Name',
        ];
    }

    /**
     * Gets query for [[Mgrfrs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMgrfrs()
    {
        return $this->hasMany(Mgrfrs::className(), ['fk_province_id' => 'id']);
    }

    /**
     * Gets query for [[Municipalities]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMunicipalities()
    {
        return $this->hasMany(Municipalities::className(), ['fk_province_id' => 'id']);
    }

    /**
     * Gets query for [[FkRegion]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkRegion()
    {
        return $this->hasOne(Regions::className(), ['id' => 'fk_region_id']);
    }
}
