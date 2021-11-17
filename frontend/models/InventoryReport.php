<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "inventory_report".
 *
 * @property int $id
 * @property string|null $date
 * @property string $created_at
 */
class InventoryReport extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'inventory_report';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date', 'created_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Date',
            'created_at' => 'Created At',
        ];
    }

    public function getInventoryReportEntries()
    {
        return $this->hasMany(InventoryReportEntries::class, ['inventory_report_id' => 'id']);
    }

}
