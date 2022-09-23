<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pr_purchase_order".
 *
 * @property int $id
 * @property string $po_number
 * @property int $fk_contract_type_id
 * @property int $fk_mode_of_procurement_id
 * @property int $fk_pr_aoq_id
 * @property string|null $place_of_delivery
 * @property string|null $delivery_date
 * @property string|null $payment_term
 * @property int|null $fk_auth_official
 * @property int|null $fk_accounting_unit
 *
 * @property PrPurchaseOrderItem[] $prPurchaseOrderItems
 */
class PrPurchaseOrder extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pr_purchase_order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'id', 'po_number', 'fk_contract_type_id', 'fk_mode_of_procurement_id', 'fk_pr_aoq_id', 'delivery_term',
                'fk_auth_official',
                'fk_accounting_unit',
                'po_date'
            ], 'required'],
            [[
                'id', 'fk_contract_type_id', 'fk_mode_of_procurement_id', 'fk_pr_aoq_id',
                'fk_auth_official', 'fk_accounting_unit', 'fk_bac_composition_id'
            ], 'integer'],
            [['place_of_delivery'], 'string'],
            [[
                'delivery_date', 'bac_date', 'fk_requested_by', 'fk_inspected_by',
                'date_work_begun',
                'date_completed'

            ], 'safe'],
            [['po_number', 'payment_term', 'delivery_term', 'po_date'], 'string', 'max' => 255],
            [['po_number'], 'unique'],
            [['id'], 'unique'],
            [[
                'id',
                'po_number',
                'fk_contract_type_id',
                'fk_mode_of_procurement_id',
                'fk_pr_aoq_id',
                'place_of_delivery',
                'delivery_date',
                'delivery_term',
                'payment_term',
                'fk_auth_official',
                'fk_accounting_unit',
                'created_at',
                'fk_bac_composition_id',
                'bac_date',
                'po_date',
                'fk_requested_by',
                'fk_inspected_by',
                'date_work_begun',
                'date_completed',

            ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'po_number' => 'PO Number',
            'fk_contract_type_id' => ' Contract Type ',
            'fk_mode_of_procurement_id' => ' Mode Of Procurement ',
            'fk_pr_aoq_id' => ' AOQ Number',
            'place_of_delivery' => 'Place Of Delivery',
            'delivery_date' => 'Delivery Date',
            'payment_term' => 'Payment Term',
            'delivery_term' => 'Delivery Term',
            'fk_auth_official' => ' Authorize Official',
            'fk_accounting_unit' => ' Accounting Unit',
            'fk_bac_composition_id' => 'BAC RSO Number',
            'bac_date' => 'BAC Date',
            'po_date' => 'Date',
            'fk_requested_by' => 'Requested By',
            'fk_inspected_by' => 'Inspected By',
            'date_work_begun' => 'Date Work Begun',
            'date_completed' => 'Date Completed',



        ];
    }

    /**
     * Gets query for [[PrPurchaseOrderItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPrPurchaseOrderItems()
    {
        return $this->hasMany(PrPurchaseOrderItem::class, ['fk_pr_purchase_order_id' => 'id']);
    }
    public function getModeOfProcurement()
    {

        return $this->hasOne(PrModeOfProcurement::class, ['id' => 'fk_mode_of_procurement_id']);
    }
    public function getAuthorizedOfficial()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'fk_auth_official']);
    }
    public function getAccountingUnit()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'fk_accounting_unit']);
    }
    public function getRequestedBy()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'fk_requested_by']);
    }
    public function getInspectedBy()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'fk_inspected_by']);
    }
    public function getContractType()
    {

        return $this->hasOne(PrContractType::class, ['id' => 'fk_contract_type_id']);
    }
    public function getAoq()
    {

        return $this->hasOne(PrAoq::class, ['id' => 'fk_pr_aoq_id']);
    }
}
