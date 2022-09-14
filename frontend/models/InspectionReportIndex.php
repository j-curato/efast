<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%inspection_report_index}}".
 *
 * @property int $id
 * @property string $ir_number
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
class InspectionReportIndex extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%inspection_report_index}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'ir_number'], 'required'],
            [['id'], 'integer'],
            [['inspector', 'chairperson', 'property_unit', 'requested_by_name'], 'string'],
            [['ir_number', 'rfi_number', 'division',  'po_number', 'payee'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [

            'id' => 'ID',
            'ir_number' => 'IR Number',
            'rfi_number' => 'RFI Number',
            'end_user' => 'End User',
            'purpose' => 'Purpose',
            'inspector_name' => 'Inspector',
            'responsible_center' => 'Responsibile Center',
            'po_number' => 'PO Number',
            'payee_name' => 'Payee',
            'requested_by_name' => 'Requested By',
        ];
    }
}
