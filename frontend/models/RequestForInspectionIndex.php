<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%request_for_inspection_index}}".
 *
 * @property int $id
 * @property string $rfi_number
 * @property string|null $division
 * @property string|null $unit
 * @property string|null $unit_head
 * @property string|null $inspector
 * @property string|null $chairperson
 * @property string|null $property_unit
 * @property string|null $po_number
 * @property string|null $payee
 * @property string|null $purpose
 * @property string|null $project_name
 */
class RequestForInspectionIndex extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%request_for_inspection_index}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'rfi_number'], 'required'],
            [['id'], 'integer'],
            [['unit_head', 'inspector', 'chairperson', 'property_unit', 'purpose', 'project_name'], 'string'],
            [['rfi_number', 'division', 'unit', 'po_number', 'payee'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'rfi_number' => 'Rfi Number',
            'division' => 'Division',
            'unit' => 'Unit',
            'unit_head' => 'Unit Head',
            'inspector' => 'Inspector',
            'chairperson' => 'Chairperson',
            'property_unit' => 'Property Unit',
            'po_number' => 'Po Number',
            'payee' => 'Payee',
            'purpose' => 'Purpose',
            'project_name' => 'Project Name',
            'date' => 'Date',
        ];
    }
}
