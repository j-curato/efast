<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mandatory_reserve".
 *
 * @property int $id
 * @property string|null $serial_number
 * @property string|null $reporting_period
 * @property string|null $particular
 *
 * @property Raouds[] $raouds
 */
class MandatoryReserve extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mandatory_reserve';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['particular'], 'string'],
            [['serial_number'], 'string', 'max' => 100],
            [['reporting_period'], 'string', 'max' => 40],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'serial_number' => 'Serial Number',
            'reporting_period' => 'Reporting Period',
            'particular' => 'Particular',
        ];
    }

    /**
     * Gets query for [[Raouds]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRaouds()
    {
        return $this->hasMany(Raouds::class, ['mandatory_reserve_id' => 'id']);
    }
}
