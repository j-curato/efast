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
                'fk_office_id'
            ], 'required'],
            [['id', 'pr_purchase_request_id', 'bac_composition_id', 'is_cancelled', 'fk_office_id'], 'integer'],
            [['_date', 'created_at', 'deadline', 'project_location', 'cancelled_at'], 'safe'],
            [['rfq_number', 'employee_id', 'province'], 'string', 'max' => 255],
            [['rfq_number'], 'unique'],
            [['id'], 'unique'],
            [[
                'project_location',

            ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
        ];
    }

    public function getAoqLinks()
    {
        return Yii::$app->db->createCommand("SELECT id, aoq_number,pr_aoq.is_cancelled  FROM pr_aoq WHERE pr_rfq_id = :id")
            ->bindValue(':id', $this->id)
            ->queryAll();
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'rfq_number' => 'RFQ No.',
            'pr_purchase_request_id' => 'Purchase Request ',
            '_date' => 'Date',
            'bac_composition_id' => 'Rbac Composition ',
            'employee_id' => 'Canvasser',
            'created_at' => 'Created At',
            'deadline' => 'Deadline',
            'province' => 'Province',
            'project_location' => 'Location of Project',
            'cancelled_at' => 'Cancelled_at',
            'is_cancelled' => 'is Cancelled',
            'fk_office_id' => 'Office'

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
    public function getOffice()
    {
        return $this->hasOne(Office::class, ['id' => 'fk_office_id']);
    }
}
