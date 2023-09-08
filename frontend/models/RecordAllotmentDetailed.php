<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%record_allotment_detailed}}".
 *
 * @property int $id
 * @property int $allotment_entry_id
 * @property string|null $allotmentNumber
 * @property string|null $budget_year
 * @property string|null $office_name
 * @property string|null $division
 * @property string|null $mfo_name
 * @property string|null $fund_source_name
 * @property string|null $account_title
 * @property float $amount
 * @property string|null $book_name
 * @property float|null $ttlOrsAmt
 * @property float|null $ttlPrAmt
 * @property float|null $ttlTrAmt
 * @property float $balance
 * @property float $balAfterObligation
 * @property string|null $reporting_period
 * @property string|null $date_issued
 * @property string|null $valid_until
 * @property string|null $particulars
 * @property string|null $document_recieve
 * @property string|null $fund_cluster_code
 * @property string|null $financing_source_code
 * @property string|null $fund_classification
 * @property string|null $authorization_code
 * @property string|null $responsibility_center
 * @property string|null $allotment_class
 * @property string|null $nca_nta
 * @property string|null $carp_101
 * @property string|null $book
 * @property string|null $allotment_type
 * @property int|null $chart_of_account_id
 */
class RecordAllotmentDetailed extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%record_allotment_detailed}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'allotment_entry_id', 'chart_of_account_id'], 'integer'],
            [['amount'], 'required'],
            [['amount', 'ttlOrsAmt', 'ttlPrAmt', 'ttlTrAmt', 'balance', 'balAfterObligation'], 'number'],
            [['particulars', 'allotment_type'], 'string'],
            [['allotmentNumber', 'date_issued', 'valid_until'], 'string', 'max' => 50],
            [['budget_year', 'carp_101'], 'string', 'max' => 4],
            [['office_name', 'division', 'fund_source_name', 'account_title', 'book_name', 'document_recieve', 'fund_cluster_code', 'financing_source_code', 'fund_classification', 'authorization_code', 'responsibility_center', 'allotment_class', 'book'], 'string', 'max' => 255],
            [['mfo_name'], 'string', 'max' => 511],
            [['reporting_period'], 'string', 'max' => 20],
            [['nca_nta'], 'string', 'max' => 3],
        ];
    }
    public static function getStatusOfFundsPerMfo($from_period, $to_period)
    {
        return Yii::$app->db->createCommand("SELECT 
                record_allotment_detailed.allotment_class,
                record_allotment_detailed.mfo_name,
                record_allotment_detailed.document_recieve,
                SUM(record_allotment_detailed.amount) as ttlAllotment,
                SUM(record_allotment_detailed.ttlOrsAmt) as ttlOrs,
                SUM(record_allotment_detailed.ttlAdjustment) as ttlAdjustment,
                SUM(record_allotment_detailed.balAfterObligation) as ttlBalance
                FROM record_allotment_detailed
                WHERE record_allotment_detailed.reporting_period >= :from_period
                AND record_allotment_detailed.reporting_period <= :to_period
                GROUP BY record_allotment_detailed.allotment_class,
                record_allotment_detailed.mfo_name,
                record_allotment_detailed.document_recieve
                ORDER BY record_allotment_detailed.allotment_class DESC")
            ->bindValue(':from_period', $from_period)
            ->bindValue(':to_period', $to_period)
            ->queryAll();
    }
    public static function getStatusOfFundsPerOffice($from_period, $to_period)
    {
        return Yii::$app->db->createCommand("SELECT 
            record_allotment_detailed.allotment_class,
            record_allotment_detailed.office_name,
            record_allotment_detailed.division,
            record_allotment_detailed.document_recieve,
            SUM(record_allotment_detailed.amount) as ttlAllotment,
            SUM(record_allotment_detailed.ttlOrsAmt) as ttlOrs,
            SUM(record_allotment_detailed.ttlAdjustment) as ttlAdjustment,
            SUM(record_allotment_detailed.balAfterObligation) as ttlBalance
            FROM record_allotment_detailed
            WHERE  record_allotment_detailed.reporting_period >= :from_period
                AND record_allotment_detailed.reporting_period <= :to_period
            GROUP BY record_allotment_detailed.allotment_class,
                record_allotment_detailed.office_name,
                record_allotment_detailed.division,
                record_allotment_detailed.document_recieve
            ORDER BY record_allotment_detailed.allotment_class DESC")
            ->bindValue(':from_period', $from_period)
            ->bindValue(':to_period', $to_period)
            ->queryAll();
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'allotment_entry_id' => 'Allotment Entry ID',
            'allotmentNumber' => 'Allotment Number',
            'budget_year' => 'Budget Year',
            'office_name' => 'Office Name',
            'division' => 'Division',
            'mfo_name' => 'Mfo Name',
            'fund_source_name' => 'Fund Source Name',
            'account_title' => 'Account Title',
            'amount' => 'Amount',
            'book_name' => 'Book Name',
            'ttlOrsAmt' => 'Ttl Ors Amt',
            'ttlPrAmt' => 'Ttl Pr Amt',
            'ttlTrAmt' => 'Ttl Tr Amt',
            'balance' => 'Bal After PR/Txn/Obligation',
            'balAfterObligation' => 'Bal After Obligation',
            'reporting_period' => 'Reporting Period',
            'date_issued' => 'Date Issued',
            'valid_until' => 'Valid Until',
            'particulars' => 'Particulars',
            'document_recieve' => 'Document Recieve',
            'fund_cluster_code' => 'Fund Cluster Code',
            'financing_source_code' => 'Financing Source Code',
            'fund_classification' => 'Fund Classification',
            'authorization_code' => 'Authorization Code',
            'responsibility_center' => 'Responsibility Center',
            'allotment_class' => 'Allotment Class',
            'nca_nta' => 'Nca Nta',
            'carp_101' => 'Carp  101',
            'book' => 'Book',
            'allotment_type' => 'Allotment Type',
            'chart_of_account_id' => 'Chart Of Account ID',
        ];
    }
}
