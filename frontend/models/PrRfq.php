<?php

namespace app\models;

use app\behaviors\GenerateIdBehavior;
use app\behaviors\HistoryLogsBehavior;
use DateTime;
use ErrorException;
use Yii;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\ArrayHelper;

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
    public function behaviors()
    {
        return [
            HistoryLogsBehavior::class,
            GenerateIdBehavior::class
        ];
    }
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
                'fk_office_id',
                'is_early_procurement',
                'source_of_fund',
                // 'mooe_amount',
                // 'co_amount',
                'fk_mode_of_procurement_id',
            ], 'required'],
            [[
                'id', 'pr_purchase_request_id', 'bac_composition_id', 'is_cancelled', 'fk_office_id', 'is_deleted',

                'is_early_procurement',

                'fk_mode_of_procurement_id'
            ], 'integer'],
            [['mooe_amount', 'co_amount'], 'number'],
            [[
                '_date', 'created_at',
                'deadline',
                'project_location',
                'cancelled_at',
                'pre_proc_conference',
                'philgeps_reference_num',
                'pre_bid_conf',
                'eligibility_check',
                'opening_of_bids',
                'bid_evaluation',
                'post_qual',
                'post_of_ib',

            ], 'safe'],
            [['rfq_number', 'employee_id', 'province', 'source_of_fund'], 'string', 'max' => 255],
            [['rfq_number'], 'unique'],
            [['id'], 'unique'],
            [[
                'project_location',
            ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
            [['_date', 'deadline'], 'validateDateToPr'],
            [[
                'pre_proc_conference',
                'philgeps_reference_num',
                'pre_bid_conf',
                'eligibility_check',
                'opening_of_bids',
                'bid_evaluation',
                'post_qual',
                'post_of_ib',

            ], 'required', 'when' => function ($model) {

                if (!empty($model->modeOfProcurement->is_bidding)) {

                    return $model->modeOfProcurement->is_bidding == 1;
                }
                return false;
            }, 'whenClient' => "function (attribute, value,model) {}"],
        ];
    }
    public function validateDateToPr($attribute, $params)
    {
        $selectedDate = $this->$attribute;
        $pr_date  = Yii::$app->db->createCommand("SELECT `date`  FROM pr_purchase_request  WHERE id = :id")
            ->bindValue(':id', $this->pr_purchase_request_id)
            ->queryOne();
        if (strtotime($selectedDate) < strtotime($pr_date['date'])) {
            $this->addError($this->getAttributeLabel($attribute), 'The date should not precede the PR date.');
        }
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
            'is_early_procurement' => 'is Early Procurement',
            'source_of_fund' => 'Source of Fund',
            'mooe_amount' => 'MOOE Amount',
            'co_amount' => 'CO Amount',
            'fk_mode_of_procurement_id' => 'Mode of Procurement',
            'pre_proc_conference' => 'Pre Proc Conference',
            'philgeps_reference_num' => 'Philgheps Reference',
            'pre_bid_conf' => 'Pre Bid Conf',
            'eligibility_check' => 'Eligibility Check',
            'opening_of_bids' => 'Opening of Bids',
            'bid_evaluation' => 'Bid Evaluation',
            'post_qual' =>  'Post Qual',
            'post_of_ib' => 'Ads/Post of IB',

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
        return $this->hasOne(Employee::class, ['employee_id' => 'employee_id']);
    }
    public function getOffice()
    {
        return $this->hasOne(Office::class, ['id' => 'fk_office_id']);
    }
    public function getNoticeOfPostponement()
    {
        return $this->hasOne(NoticeOfPostponementItems::class, ['fk_rfq_id' => 'id']);
    }
    public function getObservers()
    {
        return $this->hasMany(RfqObservers::class, ['fk_rfq_id' => 'id'])
            ->andWhere(['is_deleted' => false]);
    }
    public function getModeOfProcurement()
    {
        return $this->hasOne(PrModeOfProcurement::class, ['id' => 'fk_mode_of_procurement_id']);
    }
    public function getMfos()
    {
        return $this->hasMany(RfqMfos::class, ['fk_rfq_id' => 'id'])
            ->andWhere(['is_deleted' => false]);
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
    public function beforeSave($insert)
    {
        if (parent::beforeSave($this)) {
            if ($this->isNewRecord) {
                if (empty($this->rfq_number)) {
                    $this->rfq_number = $this->generateRfqNumber();
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
        return PrRfqItem::find()
            ->addSelect([
                new Expression("CAST(pr_rfq_item.id AS CHAR(50)) as id"),
                new Expression("CAST(pr_purchase_request_item.id as CHAR(50)) as pr_item_id"),
                "pr_purchase_request_item.specification",
                "pr_purchase_request_item.quantity",
                "pr_purchase_request_item.unit_cost",
                "pr_stock.stock_title",
                "pr_stock.bac_code",
                "unit_of_measure.unit_of_measure"
            ])

            ->join("JOIN", "pr_purchase_request_item", "pr_rfq_item.pr_purchase_request_item_id = pr_purchase_request_item.id")
            ->join("JOIN", "pr_purchase_request", "pr_purchase_request_item.pr_purchase_request_id = pr_purchase_request.id")
            ->join("JOIN", "pr_stock", "pr_purchase_request_item.pr_stock_id = pr_stock.id")
            ->join("LEFT JOIN", "unit_of_measure", "pr_purchase_request_item.unit_of_measure_id = unit_of_measure.id")
            ->andWhere([
                "pr_rfq_item.pr_rfq_id" => $this->id
            ])
            ->asArray()
            ->all();
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

    public function insertItems($items)
    {


        try {
            $itemModels = [];
            foreach ($items as $item) {
                if (empty($item['id'])) {

                    // $model = !empty($item['id']) ? PrRfqItem::findOne($item['id']) : new PrRfqItem();
                    $model =  new PrRfqItem();
                    $model->attributes = $item;
                    $model->pr_rfq_id = $this->id;
                    $itemModels[] = $model;
                }
            }
            foreach ($itemModels as $model) {
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException("Item Model Save Failed");
                }
            }
            // CHECK IF THE PURCHASE REQUEST ID ALREADY EXISTS IN PR_RFQ_ITEM_TABLE WITH THE SAME RFQ_ID
            // $check = Yii::$app->db->createCommand("SELECT EXISTS(SELECT 1 FROM pr_rfq_item WHERE pr_rfq_id = :rfq_id AND pr_purchase_request_item_id  = :pr_item_id)")
            //     ->bindValue(':rfq_id', $model_id)
            //     ->bindValue(':pr_item_id', $itm['pr_id'])
            //     ->queryScalar();
            // if ($check != 1) {
            //     $rfq_item = new PrRfqItem();
            //     $rfq_item->pr_rfq_id = $model_id;
            //     $rfq_item->pr_purchase_request_item_id = $itm['pr_id'];
            //     if (!$rfq_item->validate()) {
            //         return $rfq_item->errors;
            //     }
            //     if (!$rfq_item->save(false)) {
            //         return 'RFQ Item save failed';
            //     }
            // }
            return true;
        } catch (ErrorException $e) {
            return $e->getMessage();
        }
    }
    public function insertObservers($observers)
    {
        try {
 
            $this->deletedObservers($observers);
            $observerModels  = [];
            foreach ($observers as $observer) {
                $model = !empty($observer['id']) ? RfqObservers::findOne($observer['id']) : new RfqObservers();
                $model->attributes = $observer;
                $model->fk_rfq_id = $this->id;
                $observerModels[] = $model;
            }

            foreach ($observerModels as $model) {
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException("Observer Item Save Failed");
                }
            }
            return true;
        } catch (ErrorException $e) {
            return $e->getMessage();
        }
    }
    private function deletedObservers($observers)
    {
        $params = [];
        $ids = array_column($observers, 'id');
        $sql = '';
        if (!empty($ids)) {
            $sql  = ' AND ';
            $sql .= Yii::$app->db->queryBuilder->buildCondition(['NOT IN', 'id', $ids], $params);
        }
        Yii::$app->db->createCommand("UPDATE tbl_rfq_observers
            SET tbl_rfq_observers.is_deleted = 1 
            WHERE tbl_rfq_observers.fk_rfq_id = :id
            AND tbl_rfq_observers.is_deleted= 0
            $sql", $params)
            ->bindValue(':id', $this->id)
            ->execute();
    }
    private function deleteMfoItems($mfoItems)
    {
        Yii::$app->db->createCommand()
            ->update('tbl_rfq_mfos', ['is_deleted' => 1], ['AND', ['NOT IN', 'fk_mfo_pap_code_id', $mfoItems], ['fk_rfq_id' => $this->id]])
            ->execute();
    }

    public function insertMfoItems($mfoItems)
    {
        try {
            if (empty($mfoItems)) {
                throw new ErrorException("MFO/PAP Cannot be Empty");
            }
            $this->deleteMfoItems($mfoItems);
            $toInsertMfoItems = array_diff($mfoItems, ArrayHelper::getColumn($this->getMfosA(), 'fk_mfo_pap_code_id'));
            $mfoItemModels  = [];
            foreach ($toInsertMfoItems as $mfoItem) {
                $model = !empty($mfoItem['id']) ? RfqMfos::findOne($mfoItem['id']) : new RfqMfos();
                $model->fk_mfo_pap_code_id = $mfoItem;
                $model->fk_rfq_id = $this->id;
                $mfoItemModels[] = $model;
            }

            foreach ($mfoItemModels as $model) {
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException("Observer Item Save Failed");
                }
            }
            return true;
        } catch (ErrorException $e) {
            return $e->getMessage();
        }
    }
    public function getMfosA()
    {
        return $this->getMfos()->addSelect(['fk_mfo_pap_code_id'])->asArray()->all();
    }
}
