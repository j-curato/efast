<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%supplemental_ppmp_index}}".
 *
 * @property int $id
 * @property int $budget_year
 * @property string $cse_type
 * @property string $serial_number
 * @property string|null $office_name
 * @property string|null $division
 * @property string|null $division_program_unit_name
 * @property string|null $activity_name
 * @property string|null $prepared_by
 * @property string|null $reviewed_by
 * @property string|null $approved_by
 * @property string|null $certified_avail
 * @property float|null $total_amount
 * @property int $ttl_qty
 */
class SupplementalPpmpIndex extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%supplemental_ppmp_index}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'budget_year', 'ttl_qty'], 'integer'],
            [['activity_name', 'prepared_by', 'reviewed_by', 'approved_by', 'certified_avail'], 'string'],
            [['total_amount'], 'number'],
            [['cse_type', 'serial_number', 'office_name', 'division', 'division_program_unit_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'budget_year' => 'Budget Year',
            'cse_type' => 'Cse Type',
            'serial_number' => 'Serial Number',
            'office_name' => 'Office ',
            'division' => 'Division',
            'division_program_unit_name' => 'Division/Program/Unit',
            'activity_name' => 'Activity Name',
            'prepared_by' => 'Prepared By',
            'reviewed_by' => 'Reviewed By',
            'approved_by' => 'Approved By',
            'certified_avail' => 'Certified Funds Available By',
            'total_amount' => 'Total Amount',
            'ttl_qty' => 'Total Quantity',
            'balance' => 'Balance',
        ];
    }
}
