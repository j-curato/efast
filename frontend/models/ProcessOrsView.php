<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "process_ors_view".
 *
 * @property int $id
 * @property string|null $serial_number
 * @property string|null $reporting_period
 * @property string|null $tracking_number
 * @property string $particular
 * @property string $account_name
 * @property string|null $allotment_uacs
 * @property string|null $allotment_general_ledger
 * @property string $ors_uacs
 * @property string $ors_general_ledger
 * @property float|null $amount
 */
class ProcessOrsView extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'process_ors_view';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['particular', 'ors_uacs', 'ors_general_ledger'], 'required'],
            [['amount'], 'number'],
            [['serial_number', 'reporting_period', 'tracking_number', 'particular', 'account_name', 'allotment_general_ledger', 'ors_general_ledger'], 'string', 'max' => 255],
            [['allotment_uacs', 'ors_uacs'], 'string', 'max' => 30],
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
            'tracking_number' => 'Tracking Number',
            'particular' => 'Particular',
            'account_name' => 'Account Name',
            'allotment_uacs' => 'Allotment Uacs',
            'allotment_general_ledger' => 'Allotment General Ledger',
            'ors_uacs' => 'Ors Uacs',
            'ors_general_ledger' => 'Ors General Ledger',
            'amount' => 'Amount',
        ];
    }
}
