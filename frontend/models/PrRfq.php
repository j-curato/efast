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
 * @property int|null $bac_composition_id
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
            [[
                'id', 'rfq_number', 'pr_purchase_request_id',
                '_date',
                'deadline',
            ], 'required'],
            [['id', 'pr_purchase_request_id', 'bac_composition_id'], 'integer'],
            [['_date', 'created_at', 'deadline', 'project_location'], 'safe'],
            [['rfq_number', 'employee_id', 'province'], 'string', 'max' => 255],
            [['rfq_number'], 'unique'],
            [['id'], 'unique'],
            [[
                'id',
                'rfq_number',
                'pr_purchase_request_id',
                '_date',
                'deadline',
                'bac_composition_id',
                'employee_id',
                'province',
                'project_location',
                'created_at',

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
            'rfq_number' => 'Rfq Number',
            'pr_purchase_request_id' => 'Purchase Request ',
            '_date' => 'Date',
            'bac_composition_id' => 'Rbac Composition ',
            'employee_id' => 'Canvasser',
            'created_at' => 'Created At',
            'deadline' => 'Deadline',
            'province' => 'Province',
            'project_location' => 'Location of Project',

        ];
    }
    public function getRfqItems()
    {
        return $this->hasMany(PrRfqItem::class, ['pr_rfq_id' => 'id']);
    }
    public function getPurchaseRequest()
    {
        return $this->hasOne(PrPurchaseRequest::class, ['id' => 'pr_purchase_request_id']);
    }
    public function getCanvasser()
    {

        return $this->hasOne(Employee::class, ['id' => 'employee_id']);
    }
}
