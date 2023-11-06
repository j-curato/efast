<?php

namespace app\models;

use DateTime;
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
                'pr_purchase_request_id',
                '_date',
                'deadline',
                'fk_office_id'
            ], 'required'],
            [['id', 'pr_purchase_request_id', 'bac_composition_id', 'is_cancelled', 'fk_office_id', 'is_deleted'], 'integer'],
            [['_date', 'created_at', 'deadline', 'project_location', 'cancelled_at'], 'safe'],
            [['rfq_number', 'employee_id', 'province'], 'string', 'max' => 255],
            [['rfq_number'], 'unique'],
            [['id'], 'unique'],
            [[
                'project_location',
            ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
        ];
    }
    public function beforeSave($insert)
    {
        if (parent::beforeSave($this)) {
            if ($this->isNewRecord) {
                if (empty($this->rfq_number)) {
                    $this->rfq_number = $this->generateRfqNumber();
                }
                if (empty($this->id)) {
                    $this->id =  Yii::$app->db->createCommand("SELECT UUID_SHORT()  % 9223372036854775807")->queryScalar();
                }
            }
            return true;
        }
        return false;
    }

    public function getAoqLinks()
    {
        return Yii::$app->db->createCommand("SELECT id, aoq_number,pr_aoq.is_cancelled  FROM pr_aoq WHERE pr_rfq_id = :id")
            ->bindValue(':id', $this->id)
            ->queryAll();
    }
    public function getItems()
    {

        return Yii::$app->db->createCommand("SELECT 
            pr_rfq_item.id as item_id,
            pr_purchase_request_item.id as prItemId,
            pr_purchase_request_item.specification,
            pr_purchase_request_item.quantity,
            pr_purchase_request_item.unit_cost,
            pr_stock.stock_title,
            pr_stock.bac_code,
            unit_of_measure.unit_of_measure
            FROM pr_rfq_item
            JOIN pr_purchase_request_item ON pr_rfq_item.pr_purchase_request_item_id = pr_purchase_request_item.id
            JOIN pr_purchase_request ON pr_purchase_request_item.pr_purchase_request_id = pr_purchase_request.id
            JOIN pr_stock ON pr_purchase_request_item.pr_stock_id = pr_stock.id
            LEFT JOIN unit_of_measure ON pr_purchase_request_item.unit_of_measure_id = unit_of_measure.id
            WHERE pr_rfq_item.pr_rfq_id = :id")
            ->bindValue(':id', $this->id)
            ->queryAll();
    }
    private function generateRfqNumber()
    {
        $d  = DateTime::createFromFormat('Y-m-d', $this->_date);
        $query = Yii::$app->db->createCommand("SELECT CAST(SUBSTRING_INDEX(pr_rfq.rfq_number,'-',-1) AS UNSIGNED)  as last_num FROM pr_rfq 
                WHERE rfq_number LIKE :_date 
                AND fk_office_id = :office_id
                ORDER BY last_num DESC LIMIT 1")
            ->bindValue('_date', '%' . $d->format('Y') . '%')
            ->bindValue('office_id', $this->fk_office_id)
            ->queryScalar();
        $num = !empty($query) ? intval($query) + 1 : 1;
        return strtoupper($this->office->office_name) . '-' . $this->_date . '-' . str_pad($num, 4, '0', STR_PAD_LEFT);
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
            'fk_office_id' => 'Office',
            'is_deleted' => 'is Deleted',


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
    public function getNoticeOfPostponement()
    {
        return $this->hasOne(NoticeOfPostponementItems::class, ['fk_rfq_id' => 'id']);
    }
    public function getNopToDate()
    {
        return Yii::$app->db->createCommand("SELECT 
                    notice_of_postponement.to_date
                    FROM notice_of_postponement
                    JOIN notice_of_postponement_items ON notice_of_postponement.id = notice_of_postponement_items.fk_notice_of_postponement_id
                    WHERE 
                    notice_of_postponement.is_final = 1
                    AND notice_of_postponement_items.is_deleted = 0
                    AND notice_of_postponement_items.fk_rfq_id = :id")
            ->bindValue(':id', $this->id)
            ->queryScalar();
    }
}
