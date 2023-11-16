<?php

namespace app\models;

use DateTime;
use Yii;
use ErrorException;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%purchase_order_transmittal}}".
 *
 * @property int $id
 * @property string $serial_number
 * @property string $created_at
 *
 * @property PurchaseOrderTransmittalItems[] $purchaseOrderTransmittalItems
 */
class PurchaseOrderTransmittal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%purchase_order_transmittal}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_approved_by', 'date'], 'required'],
            [['fk_approved_by', 'fk_officer_in_charge'], 'integer'],
            [['id'], 'integer'],
            [['created_at', 'date'], 'safe'],
            [['serial_number'], 'string', 'max' => 255],
            [['serial_number'], 'unique'],
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
            'serial_number' => 'Serial Number',
            'created_at' => 'Created At',
            'date' => 'Date',
            'fk_approved_by' => 'Approved By',
            'fk_officer_in_charge' => 'Officer in Charge',
        ];
    }

    /**
     * Gets query for [[PurchaseOrderTransmittalItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPurchaseOrderTransmittalItems()
    {
        return $this->hasMany(PurchaseOrderTransmittalItems::class, ['fk_purchase_order_transmittal_id' => 'id']);
    }
    public function getApprovedBy()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'fk_approved_by']);
    }
    public function getOfficerInCharge()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'fk_officer_in_charge']);
    }
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                if (empty($this->id)) {
                    $this->id =    Yii::$app->db->createCommand("SELECT UUID_SHORT() % 9223372036854775807")->queryScalar();
                }
                if (empty($this->serial_number)) {
                    $this->serial_number =    $this->generateSerialNumber();
                }
            }
            return true;
        }
        return false;
    }
    private function generateSerialNumber()
    {

        $year = DateTime::createFromFormat('Y-m-d', $this->date)->format('Y');
        $last_num = Yii::$app->db->createCommand("SELECT CAST(SUBSTRING_INDEX(serial_number,'-',-1) AS UNSIGNED) as last_num 
        FROM purchase_order_transmittal
        ORDER BY last_num DESC LIMIT 1")
            ->queryScalar();
        $num = !empty($last_num) ? intval($last_num) + 1 : 1;

        return $year . '-' . str_pad($num, 4, '0', STR_PAD_LEFT);
    }
    public function insertItems($items)
    {
        try {
            $itemModels = [];
            $deleteItems = $this->deleteItems(ArrayHelper::getColumn($items, 'id'));

            foreach ($items as  $item) {
                $model =  !empty($item['id']) ? PurchaseOrderTransmittalItems::findOne($item['id']) : new PurchaseOrderTransmittalItems();
                $model->attributes = $item;
                $model->fk_purchase_order_transmittal_id = $this->id;
                $itemModels[] = $model;
            }

            foreach ($itemModels as $model) {
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Item Model Save Failed');
                }
            }

            return true;
        } catch (ErrorException $e) {
            return $e->getMessage();
        }
    }
    private function deleteItems($items)
    {
        $queryItems  = Yii::$app->db->createCommand("SELECT iar_transmittal_items.id FROM iar_transmittal_items WHERE fk_iar_transmittal_id  = :id
        AND is_deleted = 0")
            ->bindValue(':id', $this->id)
            ->queryAll();
        $toDelete = array_diff(array_column($queryItems, 'id'), $items);
        if (!empty($toDelete)) {
            $params = [];
            $sql  = ' AND ';
            $sql .= Yii::$app->db->queryBuilder->buildCondition(['IN', 'id', $toDelete], $params);
            Yii::$app->db->createCommand("UPDATE iar_transmittal_items
                SET iar_transmittal_items.is_deleted = 1 
                WHERE iar_transmittal_items.fk_iar_transmittal_id = :id
                AND iar_transmittal_items.is_deleted= 0
                $sql", $params)
                ->bindValue(':id', $this->id)
                ->execute();
        }
        return true;
    }
    public function getTransmittalItems()
    {
        return Yii::$app->db->createCommand("SELECT
                purchase_order_transmittal_items.id,
                pr_purchase_order_item.id as po_id,
                pr_purchase_order_item.serial_number,
                payee.account_name as payee,
                pr_purchase_request.purpose ,
                SUM(pr_aoq_entries.amount * pr_purchase_request_item.quantity) as total_amount
                FROM purchase_order_transmittal_items
                INNER JOIN pr_purchase_order_item ON  purchase_order_transmittal_items.fk_purchase_order_item_id = pr_purchase_order_item.id
                LEFT JOIN pr_purchase_order_items_aoq_items ON pr_purchase_order_item.id = pr_purchase_order_items_aoq_items.fk_purchase_order_item_id
                LEFT JOIN pr_aoq_entries ON pr_purchase_order_items_aoq_items.fk_aoq_entries_id = pr_aoq_entries.id
                LEFT JOIN payee ON pr_aoq_entries.payee_id = payee.id
                LEFT JOIN pr_rfq_item ON pr_aoq_entries.pr_rfq_item_id = pr_rfq_item.id
                LEFT JOIN pr_purchase_request_item ON pr_rfq_item.pr_purchase_request_item_id = pr_purchase_request_item.id
                LEFT JOIN pr_purchase_request ON pr_purchase_request_item.pr_purchase_request_id = pr_purchase_request.id
                WHERE purchase_order_transmittal_items.fk_purchase_order_transmittal_id = :id
                GROUP BY 
                purchase_order_transmittal_items.id,
                pr_purchase_order_item.id,
                pr_purchase_order_item.serial_number,
                payee.account_name,
                pr_purchase_request.purpose ")
            ->bindValue(':id', $this->id)
            ->queryAll();
    }
}
