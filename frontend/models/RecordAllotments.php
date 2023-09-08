<?php

namespace app\models;

use ErrorException;
use Yii;

/**
 * This is the model class for table "record_allotments".
 *
 * @property int $id
 * @property int|null $document_recieve_id
 * @property int|null $fund_cluster_code_id
 * @property int|null $financing_source_code_id
 * @property int|null $fund_category_and_classification_code_id
 * @property int|null $authorization_code_id
 * @property int|null $mfo_pap_code_id
 * @property int|null $fund_source_id
 * @property string $reporting_period
 * @property string $serial_number
 * @property string|null $date_issued
 * @property string|null $valid_until
 * @property string|null $particulars
 *
 * @property AuthorizationCode $authorizationCode
 * @property DocumentRecieve $documentRecieve
 * @property FinancingSourceCode $financingSourceCode
 * @property FundCategoryAndClassificationCode $fundCategoryAndClassificationCode
 * @property FundClusterCode $fundClusterCode
 * @property FundSource $fundSource
 * @property MfoPapCode $mfoPapCode
 */
class RecordAllotments extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'record_allotments';
    }

    public function init()
    {
        parent::init();
        $this->isMaf = false;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        $rules =  [
            [[
                'document_recieve_id', 'fund_cluster_code_id',
                'financing_source_code_id', 'fund_category_and_classification_code_id', 'authorization_code_id',
                'mfo_pap_code_id', 'fund_source_id',
                'office_id',
                'allotment_type_id',
                'division_id',
                'book_id',
                'fk_major_account_id',
            ], 'integer'],
            [[
                'document_recieve_id',
                'mfo_pap_code_id',
                'fund_source_id',
                'date_issued',
                'allotment_type_id',
                'reporting_period',
                'book_id',
                'isMaf',

            ], 'required'],
            [['reporting_period'], 'string', 'max' => 20],
            [['serial_number',  'date_issued', 'valid_until'], 'string', 'max' => 50],
            [['particulars'], 'string', 'max' => 500],
            [['authorization_code_id'], 'exist', 'skipOnError' => true, 'targetClass' => AuthorizationCode::class, 'targetAttribute' => ['authorization_code_id' => 'id']],
            [['document_recieve_id'], 'exist', 'skipOnError' => true, 'targetClass' => DocumentRecieve::class, 'targetAttribute' => ['document_recieve_id' => 'id']],
            [['financing_source_code_id'], 'exist', 'skipOnError' => true, 'targetClass' => FinancingSourceCode::class, 'targetAttribute' => ['financing_source_code_id' => 'id']],
            [['fund_category_and_classification_code_id'], 'exist', 'skipOnError' => true, 'targetClass' => FundCategoryAndClassificationCode::class, 'targetAttribute' => ['fund_category_and_classification_code_id' => 'id']],
            [['fund_cluster_code_id'], 'exist', 'skipOnError' => true, 'targetClass' => FundClusterCode::class, 'targetAttribute' => ['fund_cluster_code_id' => 'id']],
            [['fund_source_id'], 'exist', 'skipOnError' => true, 'targetClass' => FundSource::class, 'targetAttribute' => ['fund_source_id' => 'id']],
            [['mfo_pap_code_id'], 'exist', 'skipOnError' => true, 'targetClass' => MfoPapCode::class, 'targetAttribute' => ['mfo_pap_code_id' => 'id']],
        ];

        if (!$this->isMaf) {
            $rules[] = [
                [
                    'fund_cluster_code_id',
                    'financing_source_code_id',
                    'fund_category_and_classification_code_id',
                    'authorization_code_id',
                    'valid_until',
                    'particulars',
                    'fund_classification',
                    'office_id',
                    'division_id',

                ],
                'required'
            ];
        } else {
            $rules[] = [
                [
                    'fk_major_account_id'
                ],
                'required'
            ];
        }
        return $rules;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'document_recieve_id' => 'Document Recieve ',
            'fund_cluster_code_id' => 'Fund Cluster Code ',
            'financing_source_code_id' => 'Financing Source Code ',
            'fund_category_and_classification_code_id' => 'Fund Category And Classification Code ',
            'authorization_code_id' => 'Authorization Code ',
            'mfo_pap_code_id' => 'Mfo/Pap Code ',
            'fund_source_id' => 'Fund Source ',
            'reporting_period' => 'Reporting Period',
            'serial_number' => 'Serial Number',
            'date_issued' => 'Date Issued',
            'valid_until' => 'Valid Until',
            'particulars' => 'Particulars',
            'office_id' => 'Office',
            'allotment_type_id' => 'Allotment Type',
            'division_id' => 'Division',
            'book_id' => 'Book',
            'isMaf' => 'isMAF',
            'fk_major_account_id' => 'Major Account/Allotment Class'


        ];
    }
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if (empty($this->serial_number)) {
                $this->serial_number = $this->isMaf  ? $this->generateMafSerialNum() : 'qwe';
            }
            return true;
        }
        return false;
    }
    private function generateMafSerialNum()
    {
        $qry  = Yii::$app->db->createCommand("SELECT 
            CAST(SUBSTRING_INDEX(record_allotments.serial_number,'-',-1) AS UNSIGNED) as lst_num
            FROM record_allotments
            WHERE record_allotments.isMaf = 1
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
            Yii::$app->db->createCommand("UPDATE record_allotment_entries
                SET record_allotment_entries.is_deleted = 1 
                WHERE record_allotment_entries.record_allotment_id = :id
                AND record_allotment_entries.is_deleted= 0
                $sql", $params)
                ->bindValue(':id', $this->id)
                ->execute();
            $allotmentItem = [];

            foreach ($items as $item) {
                $allotmentItem = !empty($item['id']) ? RecordAllotmentEntries::findOne($item['id']) :  new RecordAllotmentEntries();
                $allotmentItem->attributes = $item;
                $allotmentItem->record_allotment_id = $this->id;
                $allotmentItem->isMaf = true;
                $allotmentItems[] = $allotmentItem;
            }

            foreach ($allotmentItems as  $idx => $model) {
                if (!$model->validate()) {
                    $i =  intval($idx) + 1;
                    foreach ($model->errors as $err) {
                        throw new ErrorException(json_encode(str_replace('.', '', $err[0])  .  " in Positive Table item# " . $i));
                    }
                    // throw new ErrorException(json_encode($model->errors));  
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
            Yii::$app->db->createCommand("UPDATE record_allotment_adjustments
                SET record_allotment_adjustments.is_deleted = 1 
                WHERE record_allotment_adjustments.fk_record_allotment_id = :id
                AND record_allotment_adjustments.is_deleted= 0
                $sql", $params)
                ->bindValue(':id', $this->id)
                ->execute();
            $adjustmentItms = [];
            foreach ($items as $item) {
                $adjustmentItm = !empty($item['id']) ? RecordAllotmentAdjustments::findOne($item['id']) : new RecordAllotmentAdjustments();
                $adjustmentItm->attributes = $item;
                $adjustmentItm->fk_record_allotment_id = $this->id;
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
    public  function getAllotmentItems()
    {
        return Yii::$app->db->createCommand("SELECT 
        record_allotment_entries.id as item_id,
        record_allotment_entries.chart_of_account_id,
        record_allotment_entries.amount,
        chart_of_accounts.uacs,
        chart_of_accounts.general_ledger
        FROM record_allotment_entries 
        LEFT JOIN chart_of_accounts ON record_allotment_entries.chart_of_account_id = chart_of_accounts.id
        WHERE record_allotment_entries.record_allotment_id = :id
        AND record_allotment_entries.is_deleted  = 0")
            ->bindValue(':id', $this->id)
            ->queryAll();
    }
    public function getMafItems()
    {
        return  !empty($this->id)
            ?
            Yii::$app->db->createCommand("SELECT
            record_allotment_entries.id,
            record_allotment_entries.fk_office_id,
            record_allotment_entries.fk_division_id,
            record_allotment_entries.chart_of_account_id,
            record_allotment_entries.amount,
            record_allotment_entries.amount as maskedAmount,
            CONCAT(chart_of_accounts.uacs,' - ', chart_of_accounts.general_ledger) as chartOfAcc,
            office.office_name,
            divisions.division
            FROM record_allotment_entries
            LEFT JOIN office ON record_allotment_entries.fk_office_id = office.id
            LEFT JOIN divisions ON record_allotment_entries.fk_division_id = divisions.id
            LEFT JOIN chart_of_accounts ON record_allotment_entries.chart_of_account_id = chart_of_accounts.id
            WHERE
            record_allotment_entries.is_deleted = 0
            AND record_allotment_entries.record_allotment_id = :id")
            ->bindValue(':id', $this->id)
            ->queryAll() : [];
    }
    public function getAdjustmentItems()
    {
        return !empty($this->id) ?
            Yii::$app->db->createCommand("SELECT 
            record_allotment_adjustments.id,
            record_allotment_adjustments.fk_record_allotment_entry_id as allotment_entry_id,
            record_allotment_adjustments.amount,
            record_allotment_adjustments.amount as maskedAmount,
            record_allotment_detailed.uacs,
            record_allotment_detailed.account_title,
            record_allotment_detailed.balAfterObligation,
            record_allotment_detailed.mfo_name,
            record_allotment_detailed.allotment_class,
            record_allotment_detailed.office_name,
            record_allotment_detailed.division
            FROM record_allotment_adjustments 
            JOIN record_allotment_detailed ON record_allotment_adjustments.fk_record_allotment_entry_id =record_allotment_detailed.allotment_entry_id
            WHERE record_allotment_adjustments.is_deleted = 0 
                AND record_allotment_adjustments.fk_record_allotment_id = :id")
            ->bindValue(':id', $this->id)
            ->queryAll()
            : [];
    }
    /**
     * Gets query for [[AuthorizationCode]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthorizationCode()
    {
        return $this->hasOne(AuthorizationCode::class, ['id' => 'authorization_code_id']);
    }

    /**
     * Gets query for [[DocumentRecieve]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentRecieve()
    {
        return $this->hasOne(DocumentRecieve::class, ['id' => 'document_recieve_id']);
    }

    /**
     * Gets query for [[FinancingSourceCode]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFinancingSourceCode()
    {
        return $this->hasOne(FinancingSourceCode::class, ['id' => 'financing_source_code_id']);
    }

    /**
     * Gets query for [[FundCategoryAndClassificationCode]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFundCategoryAndClassificationCode()
    {
        return $this->hasOne(FundCategoryAndClassificationCode::class, ['id' => 'fund_category_and_classification_code_id']);
    }

    /**
     * Gets query for [[FundClusterCode]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFundClusterCode()
    {
        return $this->hasOne(FundClusterCode::class, ['id' => 'fund_cluster_code_id']);
    }

    /**
     * Gets query for [[FundSource]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFundSource()
    {
        return $this->hasOne(FundSource::class, ['id' => 'fund_source_id']);
    }

    /**
     * Gets query for [[MfoPapCode]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMfoPapCode()
    {
        return $this->hasOne(MfoPapCode::class, ['id' => 'mfo_pap_code_id']);
    }
    public function getBook()
    {
        return $this->hasOne(Books::class, ['id' => 'book_id']);
    }
    public function getResponsibilityCenter()
    {
        return $this->hasOne(ResponsibilityCenter::class, ['id' => 'responsibility_center_id']);
    }
    public function getRecordAllotmentEntries()
    {
        return $this->hasMany(RecordAllotmentEntries::class, ['record_allotment_id' => 'id']);
    }
}
