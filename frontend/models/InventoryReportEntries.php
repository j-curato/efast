<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "inventory_report_entries".
 *
 * @property int $id
 * @property string|null $pc_number
 * @property int|null $inventory_report_id
 */
class InventoryReportEntries extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'inventory_report_entries';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['inventory_report_id'], 'integer'],
            [['pc_number'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pc_number' => 'Pc Number',
            'inventory_report_id' => 'Inventory Report ID',
        ];
    }
}
