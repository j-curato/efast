<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "location".
 *
 * @property int $id
 * @property string $location
 * @property int $is_nc
 * @property int $fk_division_id
 * @property string $created_at
 */
class Location extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'location';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['location', 'fk_division_id', 'fk_office_id'], 'required'],
            [['is_nc', 'fk_division_id', 'fk_office_id'], 'integer'],
            [['created_at'], 'safe'],
            [['location'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'location' => 'Location',
            'is_nc' => 'Office/NC',
            'fk_division_id' => 'Division ',
            'fk_office_id' => 'Office',
            'created_at' => 'Created At',
        ];
    }
    public function getOffice()
    {
        return $this->hasOne(Office::class, ['id' => 'fk_office_id']);
    }
    public function getDivisions()
    {
        return $this->hasOne(Divisions::class, ['id' => 'fk_division_id']);
    }
}
