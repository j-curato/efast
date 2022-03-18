<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pr_iar".
 *
 * @property int $id
 * @property string $_date
 * @property string $reporting_period
 * @property string $invoice_number
 * @property string $invoice_date
 * @property int $fk_pr_purchase_order_id
 * @property int $fk_inspection_officer
 * @property int $fk_property_custodian
 */
class PrIar extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pr_iar';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['_date', 'reporting_period', 'invoice_number', 'invoice_date', 'fk_pr_purchase_order_id', 'fk_inspection_officer', 'fk_property_custodian'], 'required'],
            [['_date', 'invoice_date'], 'safe'],
            [['fk_pr_purchase_order_id', 'fk_inspection_officer', 'fk_property_custodian'], 'integer'],
            [['reporting_period'], 'string', 'max' => 20],
            [['invoice_number'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            '_date' => 'Date',
            'reporting_period' => 'Reporting Period',
            'invoice_number' => 'Invoice Number',
            'invoice_date' => 'Invoice Date',
            'fk_pr_purchase_order_id' => 'Pr Purchase Order',
            'fk_inspection_officer' => 'Insepection Officer',
            'fk_property_custodian' => 'Property Custodian',
        ];
    }
    public function getInspectionOfficer()
    {
        return $this->hasOne(Employee::class, ['employee_id', 'fk_inspection_officer']);
    }
    public function getPropertCustodian()
    {
        return $this->hasOne(Employee::class, ['employee_id', 'fk_property_custodian']);
    }
}
