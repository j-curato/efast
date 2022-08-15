<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%iar_index}}".
 *
 * @property int $id
 * @property string $iar_number
 * @property string|null $ir_number
 * @property string|null $rfi_number
 * @property string|null $division
 * @property string|null $unit
 * @property string|null $unit_head
 * @property string|null $inspector
 * @property string|null $chairperson
 * @property string|null $property_unit
 * @property string|null $po_number
 * @property string|null $payee
 */
class IarIndex extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%iar_index}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'iar_number'], 'required'],
            [['id'], 'integer'],
            [['unit_head', 'inspector', 'chairperson', 'property_unit'], 'string'],
            [['iar_number', 'ir_number', 'rfi_number', 'division', 'unit', 'po_number', 'payee'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'iar_number' => 'Iar Number',
            'ir_number' => 'Ir Number',
            'rfi_number' => 'Rfi Number',
            'division' => 'Division',
            'unit' => 'Unit',
            'unit_head' => 'Unit Head',
            'inspector' => 'Inspector',
            'chairperson' => 'Chairperson',
            'property_unit' => 'Property Unit',
            'po_number' => 'Po Number',
            'payee' => 'Payee',
        ];
    }
}
