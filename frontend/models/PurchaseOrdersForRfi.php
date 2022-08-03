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
            [['id', 'po_number'], 'required'],
            [['id'], 'integer'],
            [['project_name'], 'string'],
            [['po_date'], 'safe'],
            [['po_number', 'payee', 'division', 'unit'], 'string', 'max' => 255],
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
            'project_name' => 'Project Name',
            'po_date' => 'Po Date',
            'payee' => 'Payee',
            'division' => 'Division',
            'unit' => 'Unit',
        ];
    }
}
