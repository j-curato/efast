<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%purchase_orders_for_rfi}}".
 *
 * @property int $id
 * @property string $po_number
 * @property string|null $project_name
 * @property string|null $po_date
 * @property string|null $payee
 * @property string|null $division
 * @property string|null $unit
 */
class PurchaseOrdersForRfi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%purchase_orders_for_rfi}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['po_aoq_item_id'], 'integer'],
            [[
                'po_number', 'payee', 'division', 'unit',
                'po_number',
                'project_title',
                'pr_requested_by',
                'purpose',
                'stock_title',
                'specification',
                'unit_of_measure',

            ], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [

            'division' => 'Division',
            'unit' => 'Unit',
            'po_aoq_item_id' => 'ID',
            'po_number' => 'PO No.',
            'project_title' => 'Project Name',
            'pr_requested_by' => 'Requested By',
            'purpose' => 'Purpose',
            'stock_title' => 'Stock Name',
            'specification' => 'Specification',
            'unit_of_measure' => 'Unit of Measure',
            'quantity' => 'Quantity',
            'unit_cost' => 'Unit Cost',
            'payee' => 'Payee'


        ];
    }
}
