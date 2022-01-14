<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pr_rfq".
 *
 * @property int $id
 * @property string $rfq_number
 * @property int|null $pr_purchase_request_id
 * @property string|null $_date
 * @property int|null $rbac_composition_id
 * @property string|null $employee_id
 * @property string $created_at
 */
class PrRfq extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pr_rfq';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'rfq_number'], 'required'],
            [['id', 'pr_purchase_request_id', 'rbac_composition_id'], 'integer'],
            [['_date', 'created_at','deadline'], 'safe'],
            [['rfq_number', 'employee_id'], 'string', 'max' => 255],
            [['rfq_number'], 'unique'],
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
            'rfq_number' => 'Rfq Number',
            'pr_purchase_request_id' => 'Pr Purchase Request ID',
            '_date' => 'Date',
            'rbac_composition_id' => 'Rbac Composition ID',
            'employee_id' => 'Employee ID',
            'created_at' => 'Created At',
            'deadline'=>'Deadline'
        ];
    }
    public function getRfqItems()
    {
        return $this->hasMany(PrRfqItem::class,['pr_rfq_id'=>'id']);
    }
}
