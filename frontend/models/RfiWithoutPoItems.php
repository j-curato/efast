<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%rfi_without_po_items}}".
 *
 * @property int $id
 * @property int $fk_request_for_inspection_id
 * @property string $project_name
 * @property int|null $fk_stock_id
 * @property string|null $specification
 * @property int $fk_unit_of_measure_id
 * @property int $fk_payee_id
 * @property float|null $unit_cost
 * @property int $quantity
 * @property string $from_date
 * @property string $to_date
 * @property string $created_at
 */
class RfiWithoutPoItems extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%rfi_without_po_items}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_request_for_inspection_id', 'project_name', 'fk_unit_of_measure_id', 'fk_payee_id', 'quantity', 'from_date', 'to_date'], 'required'],
            [['fk_request_for_inspection_id', 'fk_stock_id', 'fk_unit_of_measure_id', 'fk_payee_id', 'quantity', 'is_deleted'], 'integer'],
            [['project_name', 'specification'], 'string'],
            [['unit_cost'], 'number'],
            [['from_date', 'to_date', 'created_at'], 'safe'],

            [[
                'project_name',
                'specification',
                'unit_cost',
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
            'fk_request_for_inspection_id' => 'Fk Request For Inspection ID',
            'project_name' => 'Project Name',
            'fk_stock_id' => 'Fk Stock ID',
            'specification' => 'Specification',
            'fk_unit_of_measure_id' => 'Fk Unit Of Measure ID',
            'fk_payee_id' => 'Fk Payee ID',
            'unit_cost' => 'Unit Cost',
            'quantity' => 'Quantity',
            'from_date' => 'From Date',
            'to_date' => 'To Date',
            'created_at' => 'Created At',
            'is_deleted' => 'IsDeleted',
        ];
    }
}
