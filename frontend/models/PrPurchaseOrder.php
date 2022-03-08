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
            [['id', 'po_number', 'fk_contract_type_id', 'fk_mode_of_procurement_id', 'fk_pr_aoq_id', 'delivery_term'], 'required'],
            [['id', 'fk_contract_type_id', 'fk_mode_of_procurement_id', 'fk_pr_aoq_id', 'fk_auth_official', 'fk_accounting_unit'], 'integer'],
            [['place_of_delivery'], 'string'],
            [['delivery_date'], 'safe'],
            [['po_number', 'payment_term', 'delivery_term'], 'string', 'max' => 255],
            [['po_number'], 'unique'],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'po_number' => 'Po Number',
            'fk_contract_type_id' => 'Fk Contract Type ID',
            'fk_mode_of_procurement_id' => 'Fk Mode Of Procurement ID',
            'fk_pr_aoq_id' => 'Fk Pr Aoq ID',
            'place_of_delivery' => 'Place Of Delivery',
            'delivery_date' => 'Delivery Date',
            'payment_term' => 'Payment Term',
            'delivery_term' => 'Delivery Term',
            'fk_auth_official' => 'Fk Auth Official',
            'fk_accounting_unit' => 'Fk Accounting Unit',
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
}
