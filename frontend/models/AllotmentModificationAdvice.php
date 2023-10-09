<?php

namespace app\models;

use app\components\helpers\MyHelper;
use ErrorException;
use Yii;

/**
 * This is the model class for table "allotment_modification_advice".
 *
 * @property int $id
 * @property string $date
 * @property string|null $particulars
 * @property string $reporting_period
 * @property int $fk_book_id
 * @property int $fk_allotment_type_id
 * @property int $fk_mfo_pap_id
 * @property int $fk_document_receive_id
 * @property int $fk_fund_source
 * @property string $created_at
 *
 * @property AllotmentType $fkAllotmentType
 * @property Books $fkBook
 * @property Divisions $fkDivision
 * @property DocumentRecieve $fkDocumentReceive
 * @property FundSource $fkFundSource
 * @property MfoPapCode $fkMfoPap
 * @property Office $fkOffice
 * @property AllotmentModificationAdviceItems[] $allotmentModificationAdviceItems
 */
class AllotmentModificationAdvice extends \yii\db\ActiveRecord
{
    public $isMaf = true;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'allotment_modification_advice';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        $rules = [
            [[
                'date',   'fk_book_id', 'fk_allotment_type_id', 'fk_mfo_pap_id', 'fk_document_receive_id', 'fk_fund_source',
            ], 'required'],
            [['id',   'fk_book_id', 'fk_allotment_type_id', 'fk_mfo_pap_id', 'fk_document_receive_id', 'fk_fund_source'], 'integer'],
            [['date', 'created_at'], 'safe'],
            [['particulars', 'serial_num'], 'string'],
            [['reporting_period'], 'string', 'max' => 255],
            [['id'], 'unique'],
            [['fk_allotment_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => AllotmentType::class, 'targetAttribute' => ['fk_allotment_type_id' => 'id']],
            [['fk_book_id'], 'exist', 'skipOnError' => true, 'targetClass' => Books::class, 'targetAttribute' => ['fk_book_id' => 'id']],
            [['fk_document_receive_id'], 'exist', 'skipOnError' => true, 'targetClass' => DocumentRecieve::class, 'targetAttribute' => ['fk_document_receive_id' => 'id']],
            [['fk_fund_source'], 'exist', 'skipOnError' => true, 'targetClass' => FundSource::class, 'targetAttribute' => ['fk_fund_source' => 'id']],
            [['fk_mfo_pap_id'], 'exist', 'skipOnError' => true, 'targetClass' => MfoPapCode::class, 'targetAttribute' => ['fk_mfo_pap_id' => 'id']],

        ];
        if ($this->isMaf) {
            $rules[] = ['reporting_period', 'required'];
        }
        return $rules;
    }
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                if (empty($this->id)) {
                    $this->id = MyHelper::getUuid();
                }
                if (empty($this->serial_num)) {
                    $this->serial_num = $this->generateSerialNum();
                }
            }
            return true;
        }
        return false;
    }
    private function generateSerialNum()
    {

        $qry  = Yii::$app->db->createCommand("SELECT 
        CAST(SUBSTRING_INDEX(allotment_modification_advice.serial_num,'-',-1) AS UNSIGNED) as lst_num
        FROM allotment_modification_advice
        ORDER BY lst_num  DESC LIMIT 1")
            ->queryScalar();
        $lastNum = !empty($qry) ? intval($qry) + 1 : 1;
        return '2023-' . str_pad($lastNum, 4, '0', STR_PAD_LEFT);
    }
    public function insertMafItems($items)
    {
        try {
            if (empty($items)) {
                throw new ErrorException('Insert Items');
            }
            $sql = '';
            $params = [];
            $ids = array_column($items, 'id');

            if (!empty($ids)) {
                $sql  = ' AND ';
                $sql .= Yii::$app->db->queryBuilder->buildCondition(['NOT IN', 'id', $ids], $params);
            }
            Yii::$app->db->createCommand("UPDATE allotment_modification_advice_items
                SET allotment_modification_advice_items.is_deleted = 1 
                WHERE allotment_modification_advice_items.fk_allotment_modification_advice_id = :id
                AND allotment_modification_advice_items.is_deleted= 0
                $sql", $params)
                ->bindValue(':id', $this->id)
                ->execute();
            foreach ($items as $item) {
                $allotmentModificationItem = !empty($item['id']) ? AllotmentModificationAdviceItems::findOne($item['id']) : new AllotmentModificationAdviceItems();
                $allotmentModificationItem->attributes = $item;
                $allotmentModificationItem->fk_allotment_modification_advice_id = $this->id;
                $allotmentModificationItems[] = $allotmentModificationItem;
            }
            foreach ($allotmentModificationItems as $model) {
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                $model->save(false); // Save each model without re-validating
                if (!$model->save(false)) {
                    throw new ErrorException('Item model save failed');
                }
            }
            return true;
        } catch (ErrorException $e) {
            return $e->getMessage();
        }
    }
    public function insertAdjsutmentItems($items)
    {
        try {

            if (empty($items)) {
                throw new ErrorException('Insert Adjustment Items');
            }

            $sql = '';
            $params = [];
            $ids = array_column($items, 'id');

            if (!empty($ids)) {
                $sql  = ' AND ';
                $sql .= Yii::$app->db->queryBuilder->buildCondition(['NOT IN', 'id', $ids], $params);
            }
            Yii::$app->db->createCommand("UPDATE allotment_modification_advice_adjustment_items
                SET allotment_modification_advice_adjustment_items.is_deleted = 1 
                WHERE allotment_modification_advice_adjustment_items.fk_allotment_modification_advice_id = :id
                AND allotment_modification_advice_adjustment_items.is_deleted= 0
                $sql", $params)
                ->bindValue(':id', $this->id)
                ->execute();
            $adjustmentItms = [];
            foreach ($items as $item) {
                $adjustmentItm = !empty($item['id']) ? AllotmentModificationAdviceAdjustmentItems::findOne($item['id']) : new AllotmentModificationAdviceAdjustmentItems();
                $adjustmentItm->attributes = $item;
                $adjustmentItm->fk_allotment_modification_advice_id = $this->id;
                $adjustmentItms[] = $adjustmentItm;
            }

            foreach ($adjustmentItms as $idx => $itemModel) {

                if (!$itemModel->validate()) {
                    $i =  intval($idx) + 1;
                    foreach ($itemModel->errors as $err) {
                        throw new ErrorException(json_encode(str_replace('.', '', $err[0])  .  " in Source Allotments Table item #" . $i));
                    }
                    // throw new ErrorException(json_encode($itemModel->errors));
                }
                if (!$itemModel->save(false)) {
                    throw new ErrorException('Adjustment Item Model Save Failed');
                }
            }
            return true;
        } catch (ErrorException $e) {
            return $e->getMessage();
        }
    }
    public function getMafItems()
    {
        return  !empty($this->id)
            ?
            Yii::$app->db->createCommand("SELECT
                    allotment_modification_advice_items.id,
                    allotment_modification_advice_items.fk_office_id,
                    allotment_modification_advice_items.fk_division_id,
                    allotment_modification_advice_items.fk_chart_of_account_id,
                    allotment_modification_advice_items.amount,
                    allotment_modification_advice_items.amount as maskedAmount,
                    CONCAT(chart_of_accounts.uacs,' - ', chart_of_accounts.general_ledger) as chartOfAcc,
                    office.office_name,
                    divisions.division
                    FROM allotment_modification_advice_items
                    LEFT JOIN office ON allotment_modification_advice_items.fk_office_id = office.id
                    LEFT JOIN divisions ON allotment_modification_advice_items.fk_division_id = divisions.id
                    LEFT JOIN chart_of_accounts ON allotment_modification_advice_items.fk_chart_of_account_id = chart_of_accounts.id
                    WHERE 
                    allotment_modification_advice_items.is_deleted = 0
                AND fk_allotment_modification_advice_id = :id ")
            ->bindValue(':id', $this->id)
            ->queryAll() : [];
    }
    public function getAdjustmentItems()
    {
        return !empty($this->id) ?
            Yii::$app->db->createCommand("SELECT 
                allotment_modification_advice_adjustment_items.id,
                allotment_modification_advice_adjustment_items.fk_record_allotment_entry_id as allotment_entry_id,
                allotment_modification_advice_adjustment_items.amount,
                allotment_modification_advice_adjustment_items.amount as maskedAmount,
                record_allotment_detailed.uacs,
                record_allotment_detailed.account_title,
                record_allotment_detailed.balAfterObligation,
                record_allotment_detailed.mfo_name,
                record_allotment_detailed.allotment_class,
                record_allotment_detailed.office_name,
                record_allotment_detailed.division
                FROM allotment_modification_advice_adjustment_items 
                JOIN record_allotment_detailed ON allotment_modification_advice_adjustment_items.fk_record_allotment_entry_id =record_allotment_detailed.allotment_entry_id
                WHERE allotment_modification_advice_adjustment_items.is_deleted = 0 
                AND allotment_modification_advice_adjustment_items.fk_allotment_modification_advice_id = :id")
            ->bindValue(':id', $this->id)
            ->queryAll()
            : [];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Date',
            'particulars' => 'Particulars',
            'reporting_period' => 'Reporting Period',

            'fk_book_id' => ' Book ',
            'fk_allotment_type_id' => ' Allotment Type ',
            'fk_mfo_pap_id' => ' Mfo Pap ',
            'fk_document_receive_id' => ' Document Receive ',
            'fk_fund_source' => ' Fund Source',
            'created_at' => 'Created At',
            'serial_num' => 'Serial No.'
        ];
    }

    /**
     * Gets query for [[FkAllotmentType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAllotmentType()
    {
        return $this->hasOne(AllotmentType::class, ['id' => 'fk_allotment_type_id']);
    }

    /**
     * Gets query for [[FkBook]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBook()
    {
        return $this->hasOne(Books::class, ['id' => 'fk_book_id']);
    }


    /**
     * Gets query for [[FkDocumentReceive]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentReceive()
    {
        return $this->hasOne(DocumentRecieve::class, ['id' => 'fk_document_receive_id']);
    }

    /**
     * Gets query for [[FkFundSource]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFundSource()
    {
        return $this->hasOne(FundSource::class, ['id' => 'fk_fund_source']);
    }

    /**
     * Gets query for [[FkMfoPap]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMfoPap()
    {
        return $this->hasOne(MfoPapCode::class, ['id' => 'fk_mfo_pap_id']);
    }


    /**
     * Gets query for [[AllotmentModificationAdviceItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAllotmentModificationAdviceItems()
    {
        return $this->hasMany(AllotmentModificationAdviceItems::class, ['fk_allotment_modification_advice_id' => 'id']);
    }
}
