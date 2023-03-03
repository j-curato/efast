<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "property_card".
 *
 * @property string|null $serial_number
 * @property float|null $balance
 * @property string|null $par_number
 */
class PropertyCard extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'property_card';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['balance'], 'number'],
            [['fk_par_id'], 'integer'],
            [['serial_number', 'fk_par_id'], 'required'],
            [['serial_number'], 'unique'],
            [['serial_number'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'serial_number' => 'Pc Number',
            'balance' => 'Balance',
            'fk_par_id' => 'PAR',
        ];
    }
    public function getPar()
    {
        return $this->hasOne(Par::class, ['id' => 'fk_par_id']);
    }
}
