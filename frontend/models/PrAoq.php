<?php

namespace app\models;

use Yii;
use DateTime;
use ErrorException;
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
                        pr_purchase_request_item.unit_cost,
                        pr_stock.stock_title as `description`,
                        REPLACE(pr_purchase_request_item.specification,'[n]','<br>') as specification,
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
                        WHERE pr_aoq_entries.pr_aoq_id = :id 
                        AND pr_aoq_entries.is_deleted = 0")
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
    public function checkHasPo()
    {
        return Yii::$app->db->createCommand("SELECT EXISTS(SELECT * FROM `pr_aoq` 
        JOIN pr_purchase_order ON pr_aoq.id = pr_purchase_order.fk_pr_aoq_id
        WHERE
        pr_purchase_order.is_cancelled = 0
        AND pr_aoq.id = :id
        )")
            ->bindValue(':id', $this->id)
            ->queryScalar();
    }
    public function getItems()
    {
        return Yii::$app->db->createCommand("SELECT
                pr_aoq_entries.id as item_id,
                pr_rfq_item.id as rfq_item_id,
                pr_stock.bac_code,
                unit_of_measure.unit_of_measure,
                pr_stock.stock_title,
                IFNULL(REPLACE( pr_purchase_request_item.specification, '[n]', '<br>'),'') as specification,
                pr_purchase_request_item.quantity,
                UPPER(IFNULL(payee.registered_name,payee.account_name)) as payee,
                payee.id as payee_id,
                pr_aoq_entries.amount,
                pr_aoq_entries.remark,
                pr_aoq_entries.is_lowest
                FROM pr_aoq_entries
                LEFT JOIN pr_rfq_item ON pr_aoq_entries.pr_rfq_item_id = pr_rfq_item.id
                LEFT JOIN pr_purchase_request_item ON pr_rfq_item.pr_purchase_request_item_id = pr_purchase_request_item.id
                LEFT JOIN pr_stock ON pr_purchase_request_item.pr_stock_id = pr_stock.id
                LEFT JOIN unit_of_measure ON pr_purchase_request_item.unit_of_measure_id = unit_of_measure.id
                LEFT JOIN payee ON pr_aoq_entries.payee_id = payee.id
                WHERE 
                pr_aoq_entries.is_deleted = 0
                AND
                pr_aoq_entries.pr_aoq_id = :pr_aoq_id")
            ->bindValue(':pr_aoq_id', $this->id)
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
            'fk_office_id' => 'Office',
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
    public function getOffice()
    {
        return $this->hasOne(Office::class, ['id' => 'fk_office_id']);
    }
    public function insertItems($items)
    {
        try {
            $itemModels = [];
            $deleteItems = $this->deleteItems(ArrayHelper::getColumn($items, 'id'));
            if ($deleteItems !== true) {
                throw new ErrorException($deleteItems);
            }
            foreach ($items as $item) {
                $model = !empty($item['id']) ? PrAoqEntries::findOne($item['id']) : new PrAoqEntries();
                $model->attributes = $item;
                $model->pr_aoq_id = $this->id;
                $model->is_lowest = !empty($item['is_lowest']) ? 1 : 0;
                $itemModels[] = $model;
            }
            foreach ($itemModels as $model) {
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Item Model Save Failed');
                }
            };

            return true;
        } catch (ErrorException $e) {
            return $e->getMessage();
        }
    }
    private function deleteItems($items)
    {
        $queryItems  = Yii::$app->db->createCommand("SELECT pr_aoq_entries.id FROM pr_aoq_entries WHERE pr_aoq_id  = :id
        AND is_deleted = 0")
            ->bindValue(':id', $this->id)
            ->queryAll();
        $toDelete = array_diff(array_column($queryItems, 'id'), $items);
        if (!empty($toDelete)) {
            $params = [];
            $sql  = ' AND ';
            $sql .= Yii::$app->db->queryBuilder->buildCondition(['IN', 'id', $toDelete], $params);
            Yii::$app->db->createCommand("UPDATE pr_aoq_entries
                SET pr_aoq_entries.is_deleted = 1 
                WHERE pr_aoq_entries.pr_aoq_id = :id
                AND pr_aoq_entries.is_deleted= 0
                $sql", $params)
                ->bindValue(':id', $this->id)
                ->execute();
        }
        return true;
    }
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                if (empty($this->id)) {
                    $this->id =  Yii::$app->db->createCommand("SELECT UUID_SHORT()  % 9223372036854775807")->queryScalar();
                }
                if (empty($this->aoq_number)) {
                    $this->aoq_number = $this->generateSerialNumber();
                }
            }
            return true;
        }
        return false;
    }
    private function generateSerialNumber()
    {
        $date = DateTime::createFromFormat('Y-m-d', $this->pr_date);
        $queryLastNumber  = Yii::$app->db->createCommand("SELECT CAST(substring_index(aoq_number,'-',-1) AS UNSIGNED)as last_id
        FROM pr_aoq
        WHERE fk_office_id = :office_id
        AND pr_aoq.aoq_number LIKE :yr
         ORDER BY last_id DESC LIMIT 1")
            ->bindValue(':office_id', $this->fk_office_id)
            ->bindValue(':yr', "%" . $date->format('Y') . '%')
            ->queryScalar();
        $lastNum  = !empty($queryLastNumber) ? intval($queryLastNumber) + 1 : 1;
        return $this->office->office_name . '-' . $date->format('Y-m-d') . '-' . str_pad($lastNum, 4, '0', STR_PAD_LEFT);
    }
    public function getItemPayees()
    {
        return Yii::$app->db->createCommand("SELECT IFNULL(payee.registered_name,payee.account_name) as payee
                FROM `pr_aoq_entries`
                LEFT JOIN payee ON pr_aoq_entries.payee_id = payee.id
                WHERE pr_aoq_entries.pr_aoq_id = :id
                AND pr_aoq_entries.is_deleted = 0
                GROUP BY payee")
            ->bindValue(':id', $this->id)
            ->queryAll();
    }
}
