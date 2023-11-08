<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "barangays".
 *
 * @property int $id
 * @property int $fk_municipality_id
 * @property string $barangay_name
 *
 * @property Municipalities $fkMunicipality
 * @property Mgrfrs[] $mgrfrs
 */
class Barangays extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'barangays';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_municipality_id', 'barangay_name'], 'required'],
            [['fk_municipality_id'], 'integer'],
            [['barangay_name'], 'string', 'max' => 255],
            [['fk_municipality_id'], 'exist', 'skipOnError' => true, 'targetClass' => Municipalities::class, 'targetAttribute' => ['fk_municipality_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_municipality_id' => 'Fk Municipality ID',
            'barangay_name' => 'Barangay Name',
        ];
    }

    /**
     * Gets query for [[FkMunicipality]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkMunicipality()
    {
        return $this->hasOne(Municipalities::class, ['id' => 'fk_municipality_id']);
    }

    /**
     * Gets query for [[Mgrfrs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMgrfrs()
    {
        return $this->hasMany(Mgrfrs::class, ['fk_barangay_id' => 'id']);
    }
    public static function getBarangaysByMunicipalityId($id)
    {
        return Barangays::find()->where('fk_municipality_id =:id', ['id' => $id])->asArray()->all();
    }
}
