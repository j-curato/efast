<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

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
                'id', 'po_number',
                'fk_contract_type_id',
                'fk_mode_of_procurement_id',
                'fk_pr_aoq_id', 'delivery_term',
                'fk_auth_official',
                'fk_accounting_unit',
                'po_date',
                'fk_office_id'
            ], 'required'],
            [[
                'id', 'fk_contract_type_id',
                'fk_mode_of_procurement_id',
                'fk_pr_aoq_id',
                'fk_auth_official',
                'fk_accounting_unit',
                'fk_bac_composition_id',
                'is_cancelled',
                'fk_office_id'
            ], 'integer'],
            [['place_of_delivery'], 'string'],
            [[
                'delivery_date',
                'bac_date',
                'fk_requested_by',
                'fk_inspected_by',
                'date_work_begun',
                'date_completed',
                'cancelled_at'

            ], 'safe'],
            [['po_number', 'payment_term', 'delivery_term', 'po_date'], 'string', 'max' => 255],
            [['po_number'], 'unique'],
            [['id'], 'unique'],
            [[
                'place_of_delivery',
                'delivery_date',
                'delivery_term',
                'payment_term',
            ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
        ];
    }
    public function getRfiLinks()
    {
        return Yii::$app->db->createCommand("SELECT 
                pr_purchase_order_item.serial_number as po_number,
            request_for_inspection.rfi_number,
                pr_stock.stock_title,
                request_for_inspection_items.quantity,
                request_for_inspection_items.fk_request_for_inspection_id
                FROM `pr_purchase_order_items_aoq_items`
                INNER JOIN request_for_inspection_items ON pr_purchase_order_items_aoq_items.id = request_for_inspection_items.fk_pr_purchase_order_items_aoq_item_id
                LEFT JOIN pr_aoq_entries ON pr_purchase_order_items_aoq_items.fk_aoq_entries_id = pr_aoq_entries.id
                LEFT JOIN pr_rfq_item ON pr_aoq_entries.pr_rfq_item_id  = pr_rfq_item.id
                LEFT JOIN pr_purchase_request_item ON pr_rfq_item.pr_purchase_request_item_id = pr_purchase_request_item.id
                LEFT JOIN pr_stock ON pr_purchase_request_item.pr_stock_id = pr_stock.id
                LEFT JOIN pr_purchase_order_item ON pr_purchase_order_items_aoq_items.fk_purchase_order_item_id = pr_purchase_order_item.id
                LEFT JOIN request_for_inspection ON request_for_inspection_items.fk_request_for_inspection_id = request_for_inspection.id

                WHERE 
                pr_purchase_order_item.fk_pr_purchase_order_id = :id
                AND request_for_inspection_items.is_deleted !=1")
            ->bindValue(':id', $this->id)
            ->queryAll();
    }
    public function getItems()
    {
        $query = Yii::$app->db->createCommand("SELECT 
            pr_purchase_order_item.serial_number,
            payee.registered_name as payee,
            payee.account_name,
            IFNULL(payee.tin_number,'')as tin_number, 
            IFNULL(payee.registered_address,'')as `address`, 
            pr_aoq_entries.amount as unit_cost,
            pr_aoq_entries.remark,
            pr_purchase_request_item.quantity,
            IFNULL(REPLACE( pr_purchase_request_item.specification, '[n]', '<br>'),'') as specification,
            pr_stock.stock_title as `description`,
            pr_stock.bac_code,
            unit_of_measure.unit_of_measure,
            pr_aoq_entries.amount * pr_purchase_request_item.quantity as total_cost
            
            FROM pr_purchase_order
            LEFT JOIN pr_purchase_order_item ON pr_purchase_order.id = pr_purchase_order_item.fk_pr_purchase_order_id
            LEFT JOIN pr_purchase_order_items_aoq_items ON pr_purchase_order_item.id  = pr_purchase_order_items_aoq_items.fk_purchase_order_item_id
            LEFT JOIN  pr_aoq_entries ON pr_purchase_order_items_aoq_items.fk_aoq_entries_id = pr_aoq_entries.id
            LEFT JOIN payee ON pr_aoq_entries.payee_id  = payee.id
            LEFT JOIN pr_rfq_item ON pr_aoq_entries.pr_rfq_item_id = pr_rfq_item.id
            LEFT JOIN pr_purchase_request_item ON pr_rfq_item.pr_purchase_request_item_id = pr_purchase_request_item.id
            LEFT JOIN pr_stock ON pr_purchase_request_item.pr_stock_id = pr_stock.id
            LEFT JOIN unit_of_measure on pr_purchase_request_item.unit_of_measure_id = unit_of_measure.id
            WHERE pr_purchase_order.id = :id")
            ->bindValue(':id', $this->id)
            ->queryAll();
        return  ArrayHelper::index($query, null, 'serial_number');
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
            'is_cancelled' => 'Is Cancelled',
            'cancelled_at' => 'Cancelled At',
            'fk_office_id' => 'Office'



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
