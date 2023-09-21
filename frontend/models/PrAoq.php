<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "pr_aoq".
 *
 * @property int $id
 * @property string|null $aoq_number
 * @property int|null $pr_rfq_id
 * @property string|null $pr_date
 * @property string $created_at
 */
class PrAoq extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pr_aoq';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'pr_rfq_id',
                'is_cancelled',
                'fk_office_id',
            ], 'integer'],
            [['pr_date', 'created_at', 'cancelled_at'], 'safe'],
            [['aoq_number'], 'string', 'max' => 255],
            [['aoq_number'], 'unique'],
            [['pr_date', 'pr_rfq_id', 'fk_office_id'], 'required'],
        ];
    }
    public function getViewItems()
    {
        $qry = Yii::$app->db->createCommand("SELECT 
                        pr_rfq_item.id as rfq_item_id,
                        pr_purchase_request_item.quantity,
                        pr_stock.stock_title as `description`,
                        IFNULL(REPLACE(pr_purchase_request_item.specification,'[n]','<br>'),'') as specification,
                        IFNULL(payee.registered_name,payee.account_name) as payee,
                        COALESCE(pr_aoq_entries.amount,0) as amount,
                        pr_purchase_request.purpose,
                        pr_aoq_entries.remark,
                        pr_aoq_entries.is_lowest,
                        unit_of_measure.unit_of_measure,
                        pr_rfq.bac_composition_id,
                        pr_purchase_request_item.id as pr_item_id
                        FROM `pr_aoq_entries`
                        LEFT JOIN payee ON pr_aoq_entries.payee_id = payee.id
                        LEFT JOIN pr_rfq_item ON pr_aoq_entries.pr_rfq_item_id = pr_rfq_item.id
                        LEFT JOIN pr_purchase_request_item ON pr_rfq_item.pr_purchase_request_item_id= pr_purchase_request_item.id
                        LEFT JOIN unit_of_measure ON pr_purchase_request_item.unit_of_measure_id = unit_of_measure.id
                        LEFT JOIN pr_stock ON pr_purchase_request_item.pr_stock_id  = pr_stock.id
                        LEFT JOIN pr_purchase_request ON pr_purchase_request_item.pr_purchase_request_id = pr_purchase_request.id
                        LEFT JOIN pr_rfq ON pr_rfq_item.pr_rfq_id = pr_rfq.id
                        WHERE pr_aoq_entries.pr_aoq_id = :id")
            ->bindValue(':id', $this->id)
            ->queryAll();
        return $result = ArrayHelper::index($qry, 'payee', [function ($element) {
            return $element['pr_item_id'];
        }]);
    }

    public function getPoLinks()
    {
        return Yii::$app->db->createCommand("SELECT id,po_number,pr_purchase_order.is_cancelled FROM pr_purchase_order WHERE fk_pr_aoq_id= :id")
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
            'aoq_number' => 'AOQ No.',
            'pr_rfq_id' => 'RFQ No.',
            'pr_date' => 'Date',
            'created_at' => 'Created At',
            'is_cancelled' => 'Is Cancel',
            'cancelled_at=' => 'Cancelled  At',
            'fk_office_id' => 'Office'
        ];
    }
    public function getPrAoqEntries()
    {
        return $this->hasMany(PrAoqEntries::class, ['pr_aoq_id' => 'id']);
    }
    public function getRfq()
    {
        return $this->hasOne(PrRfq::class, ['id' => 'pr_rfq_id']);
    }
}
