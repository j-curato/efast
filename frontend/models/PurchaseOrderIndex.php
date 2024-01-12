<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vw_purchase_order_index".
 *
 * @property string $po_number
 * @property string|null $purpose
 * @property string|null $division
 * @property string|null $office_name
 * @property int|null $is_cancelled
 * @property string|null $mode_of_procurement_name
 * @property string $created_at
 */
class PurchaseOrderIndex extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vw_purchase_order_index';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['po_number'], 'required'],
            [['purpose'], 'string'],
            [['is_cancelled', 'id'], 'integer'],
            [['created_at'], 'safe'],
            [['po_number', 'division', 'office_name', 'mode_of_procurement_name'], 'string', 'max' => 255],
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
            'purpose' => 'Purpose',
            'division' => 'Division',
            'office_name' => 'Office Name',
            'is_cancelled' => 'Is Cancelled',
            'mode_of_procurement_name' => 'Mode Of Procurement Name',
            'created_at' => 'Created At',
        ];
    }
}
