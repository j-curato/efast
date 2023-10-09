<?php

namespace app\models;

use Yii;
use common\models\User;

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


        // return Yii::$app->db->createCommand("SELECT 
        //     record_allotment_detailed.book_name,
        //     record_allotment_detailed.allotment_class,
        //     record_allotment_detailed.mfo_name,
        //     record_allotment_detailed.document_recieve,
        //     SUM(record_allotment_detailed.amount) as ttlAllotment,
        //     SUM(record_allotment_detailed.ttlOrsAmt) as ttlOrs,
        //     SUM(record_allotment_detailed.ttlAdjustment) as ttlAdjustment,
        //     SUM(record_allotment_detailed.balAfterObligation) as ttlBalance
        //     FROM record_allotment_detailed
        //     WHERE record_allotment_detailed.reporting_period >=:from_period
        //     AND record_allotment_detailed.reporting_period <= :to_period

        //     GROUP BY
        //     record_allotment_detailed.book_name,
        //     record_allotment_detailed.allotment_class,
        //     record_allotment_detailed.mfo_name,
        //     record_allotment_detailed.document_recieve
        //     ORDER BY record_allotment_detailed.allotment_class DESC")
        //     ->bindValue(':from_period', $from_period)
        //     ->bindValue(':to_period', $to_period)
        //     ->queryAll();

        return Yii::$app->db->createCommand("CALL prc_GetSofMfo(:from_period,:to_period)")
            ->bindValue(':from_period', $from_period)
            ->bindValue(':to_period', $to_period)
            ->queryAll();
    }
    public static function getStatusOfFundsPerOffice($from_period, $to_period)
    {
        $sql = '';
        if (!YIi::$app->user->can('ro_budget_admin')) {
            $user_data = User::getUserDetails();
            $sql = " AND cte_allotmentDetails.division = '" . $user_data->employee->empDivision->division . "'";
        }

        // return Yii::$app->db->createCommand("SELECT 
        //         record_allotment_detailed.book_name,
        //         record_allotment_detailed.allotment_class,
        //         record_allotment_detailed.office_name,
        //         record_allotment_detailed.division,
        //         record_allotment_detailed.document_recieve,
        //         SUM(record_allotment_detailed.amount) as ttlAllotment,
        //         SUM(record_allotment_detailed.ttlOrsAmt) as ttlOrs,
        //         SUM(record_allotment_detailed.ttlAdjustment) as ttlAdjustment,
        //         SUM(record_allotment_detailed.balAfterObligation) as ttlBalance
        //         FROM record_allotment_detailed
        //         WHERE  record_allotment_detailed.reporting_period >= :from_period
        //             AND record_allotment_detailed.reporting_period <= :to_period
        //             $sql
        //         GROUP BY 
        //             record_allotment_detailed.book_name,
        //             record_allotment_detailed.allotment_class,
        //             record_allotment_detailed.office_name,
        //             record_allotment_detailed.division,
        //             record_allotment_detailed.document_recieve
        //         ORDER BY record_allotment_detailed.allotment_class DESC", $params)
        //     ->bindValue(':from_period', $from_period)
        //     ->bindValue(':to_period', $to_period)
        //     ->queryAll();
        // return Yii::$app->db->createCommand("WITH  
        //     consoUsedAllotments as (
        //     SELECT 
        //     process_ors_entries.record_allotment_entries_id as allotment_entry_id,
        //     SUM(process_ors_entries.amount) as ttlOrsAmt

        //     FROM process_ors_entries
        //     JOIN process_ors ON process_ors_entries.process_ors_id = process_ors.id
        //     WHERE 
        //     process_ors.is_cancelled = 0
        //     AND process_ors_entries.reporting_period >=:from_period
        //     AND process_ors_entries.reporting_period <=:to_period
        //     GROUP BY process_ors_entries.record_allotment_entries_id
        //     ),
        //     cte_allotmentAdjustments as (
        //     SELECT 
        //     record_allotment_adjustments.fk_record_allotment_entry_id,
        //     SUM( record_allotment_adjustments.amount) as ttl
        //     FROM record_allotment_adjustments
        //     WHERE 
        //     record_allotment_adjustments.is_deleted = 0
        //     GROUP BY record_allotment_adjustments.fk_record_allotment_entry_id
        //     ),
        //     cte_allotmentDetails as (
        //     SELECT 
        //     (CASE
        //     WHEN record_allotments.isMaf =1 THEN UPPER(entryOffice.office_name)
        //     ELSE UPPER(office.office_name)
        //     END) as office_name,
        //     (CASE
        //     WHEN record_allotments.isMaf =1 THEN UPPER(entryDivision.division)
        //     ELSE UPPER(divisions.division)
        //     END) as division,
        //     books.`name` as book_name,
        //     major_accounts.`name` as allotment_class,
        //     CONCAT(mfo_pap_code.`code`,'-',mfo_pap_code.`name`) as mfo_name,
        //     document_recieve.`name` as document_recieve,
        //     record_allotment_entries.amount,
        //     COALESCE(consoUsedAllotments.ttlOrsAmt,0)as ttlOrsAmt,
        //     COALESCE(cte_allotmentAdjustments.ttl,0) as ttlAdjustment,
        //     COALESCE(record_allotment_entries.amount,0) - 	COALESCE(ABS(cte_allotmentAdjustments.ttl),0)-  COALESCE(consoUsedAllotments.ttlOrsAmt,0) as balAfterObligation,
        //     record_allotments.reporting_period
        //     FROM record_allotment_entries 
        //     INNER JOIN record_allotments ON record_allotment_entries.record_allotment_id = record_allotments.id
        //     LEFT JOIN mfo_pap_code ON record_allotments.mfo_pap_code_id = mfo_pap_code.id
        //     LEFT JOIN chart_of_accounts ON record_allotment_entries.chart_of_account_id = chart_of_accounts.id
        //     LEFT JOIN office ON record_allotments.office_id = office.id
        //     lEFT JOIN divisions ON record_allotments.division_id = divisions.id
        //     LEFT JOIN books ON record_allotments.book_id = books.id
        //     LEFT JOIN consoUsedAllotments ON record_allotment_entries.id = consoUsedAllotments.allotment_entry_id
        //     LEFT JOIN document_recieve ON record_allotments.document_recieve_id = document_recieve.id
        //     LEFT JOIN major_accounts ON chart_of_accounts.major_account_id = major_accounts.id 
        //     LEFT JOIN cte_allotmentAdjustments ON record_allotment_entries.id = cte_allotmentAdjustments.fk_record_allotment_entry_id
        //     LEFT JOIN office as entryOffice ON record_allotment_entries.fk_office_id = entryOffice.id
        //     LEFT JOIN divisions as entryDivision ON record_allotment_entries.fk_division_id = entryDivision.id
        //     WHERE record_allotment_entries.is_deleted = 0 
        //     )
        //     SELECT 


        //     cte_allotmentDetails.book_name,
        //     cte_allotmentDetails.allotment_class,
        //     cte_allotmentDetails.office_name,
        //     cte_allotmentDetails.division,
        //     cte_allotmentDetails.document_recieve,
        //     SUM(cte_allotmentDetails.amount) as ttlAllotment,
        //     SUM(cte_allotmentDetails.ttlOrsAmt) as ttlOrs,
        //     SUM(cte_allotmentDetails.ttlAdjustment) as ttlAdjustment,
        //     SUM(cte_allotmentDetails.balAfterObligation) as ttlBalance
        //     FROM cte_allotmentDetails
        //     WHERE
        //     cte_allotmentDetails.reporting_period >=:from_period
        //     AND cte_allotmentDetails.reporting_period <=:to_period
        //     GROUP BY
        //     cte_allotmentDetails.book_name,
        //     cte_allotmentDetails.allotment_class,
        //     cte_allotmentDetails.office_name,
        //     cte_allotmentDetails.division,
        //     cte_allotmentDetails.document_recieve
        //     ORDER BY cte_allotmentDetails.allotment_class DESC
        // ")
        //     ->bindValue(':from_period', $from_period)
        //     ->bindValue(':to_period', $to_period)
        //     ->queryAll();

        return Yii::$app->db->createCommand("CALL prc_GetSofOffice(:from_period,:to_period,:sql)")
            ->bindValue(':from_period', $from_period)
            ->bindValue(':to_period', $to_period)
            ->bindValue(':sql', $sql)
            ->queryAll();
    }
    public static function getStatusOfFundsPerMfoOffice($from_period, $to_period)
    {


        // return Yii::$app->db->createCommand("SELECT 
        // record_allotment_detailed.book_name,
        // record_allotment_detailed.allotment_class,
        // record_allotment_detailed.mfo_name,
        // record_allotment_detailed.office_name,
        // record_allotment_detailed.division,
        // record_allotment_detailed.document_recieve,
        // SUM(record_allotment_detailed.amount) as ttlAllotment,
        // SUM(record_allotment_detailed.ttlOrsAmt) as ttlOrs,
        // SUM(record_allotment_detailed.ttlAdjustment) as ttlAdjustment,
        // SUM(record_allotment_detailed.balAfterObligation) as ttlBalance
        // FROM record_allotment_detailed
        // WHERE  record_allotment_detailed.reporting_period >= :from_period
        // AND record_allotment_detailed.reporting_period <= :to_period
        // $sql
        // GROUP BY 
        // record_allotment_detailed.book_name,
        // record_allotment_detailed.allotment_class,
        // record_allotment_detailed.mfo_name,
        // record_allotment_detailed.office_name,
        // record_allotment_detailed.division,
        // record_allotment_detailed.document_recieve
        // ORDER BY record_allotment_detailed.allotment_class DESC", $params)
        //     ->bindValue(':from_period', $from_period)
        //     ->bindValue(':to_period', $to_period)
        //     ->queryAll();
        // return Yii::$app->db->createCommand("WITH  
        //     consoUsedAllotments as (
        //     SELECT 
        //     process_ors_entries.record_allotment_entries_id as allotment_entry_id,
        //     SUM(process_ors_entries.amount) as ttlOrsAmt

        //     FROM process_ors_entries
        //     JOIN process_ors ON process_ors_entries.process_ors_id = process_ors.id
        //     WHERE 
        //     process_ors.is_cancelled = 0
        //     AND process_ors_entries.reporting_period >=:from_period
        //     AND process_ors_entries.reporting_period <=:to_period
        //     GROUP BY process_ors_entries.record_allotment_entries_id
        //     ),
        //     cte_allotmentAdjustments as (
        //     SELECT 
        //     record_allotment_adjustments.fk_record_allotment_entry_id,
        //     SUM( record_allotment_adjustments.amount) as ttl
        //     FROM record_allotment_adjustments
        //     WHERE 
        //     record_allotment_adjustments.is_deleted = 0
        //     GROUP BY record_allotment_adjustments.fk_record_allotment_entry_id
        //     ),
        //     cte_allotmentDetails as (
        //     SELECT 
        //     (CASE
        //     WHEN record_allotments.isMaf =1 THEN UPPER(entryOffice.office_name)
        //     ELSE UPPER(office.office_name)
        //     END) as office_name,
        //     (CASE
        //     WHEN record_allotments.isMaf =1 THEN UPPER(entryDivision.division)
        //     ELSE UPPER(divisions.division)
        //     END) as division,
        //     books.`name` as book_name,
        //     major_accounts.`name` as allotment_class,
        //     CONCAT(mfo_pap_code.`code`,'-',mfo_pap_code.`name`) as mfo_name,
        //     document_recieve.`name` as document_recieve,
        //     record_allotment_entries.amount,
        //     COALESCE(consoUsedAllotments.ttlOrsAmt,0)as ttlOrsAmt,
        //     COALESCE(cte_allotmentAdjustments.ttl,0) as ttlAdjustment,
        //     COALESCE(record_allotment_entries.amount,0) - 	COALESCE(ABS(cte_allotmentAdjustments.ttl),0)-  COALESCE(consoUsedAllotments.ttlOrsAmt,0) as balAfterObligation,
        //     record_allotments.reporting_period
        //     FROM record_allotment_entries 
        //     INNER JOIN record_allotments ON record_allotment_entries.record_allotment_id = record_allotments.id
        //     LEFT JOIN mfo_pap_code ON record_allotments.mfo_pap_code_id = mfo_pap_code.id
        //     LEFT JOIN chart_of_accounts ON record_allotment_entries.chart_of_account_id = chart_of_accounts.id
        //     LEFT JOIN office ON record_allotments.office_id = office.id
        //     lEFT JOIN divisions ON record_allotments.division_id = divisions.id
        //     LEFT JOIN books ON record_allotments.book_id = books.id
        //     LEFT JOIN consoUsedAllotments ON record_allotment_entries.id = consoUsedAllotments.allotment_entry_id
        //     LEFT JOIN document_recieve ON record_allotments.document_recieve_id = document_recieve.id
        //     LEFT JOIN major_accounts ON chart_of_accounts.major_account_id = major_accounts.id 
        //     LEFT JOIN cte_allotmentAdjustments ON record_allotment_entries.id = cte_allotmentAdjustments.fk_record_allotment_entry_id
        //     LEFT JOIN office as entryOffice ON record_allotment_entries.fk_office_id = entryOffice.id
        //     LEFT JOIN divisions as entryDivision ON record_allotment_entries.fk_division_id = entryDivision.id
        //     WHERE record_allotment_entries.is_deleted = 0 
        //     )
        //     SELECT 



        //     cte_allotmentDetails.book_name,
        //     cte_allotmentDetails.allotment_class,
        //     cte_allotmentDetails.mfo_name,
        //     cte_allotmentDetails.office_name,
        //     cte_allotmentDetails.division,
        //     cte_allotmentDetails.document_recieve,


        //     SUM(cte_allotmentDetails.amount) as ttlAllotment,
        //     SUM(cte_allotmentDetails.ttlOrsAmt) as ttlOrs,
        //     SUM(cte_allotmentDetails.ttlAdjustment) as ttlAdjustment,
        //     SUM(cte_allotmentDetails.balAfterObligation) as ttlBalance
        //     FROM cte_allotmentDetails
        //     WHERE
        //     cte_allotmentDetails.reporting_period >=:from_period
        //     AND cte_allotmentDetails.reporting_period <=:to_period
        //     GROUP BY
        //     cte_allotmentDetails.book_name,
        //     cte_allotmentDetails.allotment_class,
        //     cte_allotmentDetails.mfo_name,
        //     cte_allotmentDetails.office_name,
        //     cte_allotmentDetails.division,
        //     cte_allotmentDetails.document_recieve

        //     ORDER BY cte_allotmentDetails.allotment_class DESC
        // ")
        //     ->bindValue(':from_period', $from_period)
        //     ->bindValue(':to_period', $to_period)
        //     ->queryAll();
        $sql = '';
        if (!YIi::$app->user->can('ro_budget_admin')) {
            $user_data = User::getUserDetails();
            $sql = " AND cte_allotmentDetails.division = '" . $user_data->employee->empDivision->division . "'";
        }
        return Yii::$app->db->createCommand("CALL prc_GetSofMfoOffice(:from_period,:to_period,:sql)")
            ->bindValue(':from_period', $from_period)
            ->bindValue(':to_period', $to_period)
            ->bindValue(':sql', $sql)
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
