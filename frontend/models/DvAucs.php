<?php

namespace app\models;

use Yii;
use ErrorException;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use app\behaviors\HistoryLogsBehavior;

/**
 * This is the model class for table "dv_aucs".
 *
 * @property int $id
 * @property int|null $process_ors_id
 * @property int|null $raoud_id
 * @property string|null $dv_number
 * @property string|null $reporting_period

 *
 * @property ProcessOrs $processOrs
 * @property Raouds $raoud
 */
class DvAucs extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            HistoryLogsBehavior::class
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dv_aucs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {




        return [
            [['tracking_sheet_id'], 'number'],
            [[
                'fk_dv_transaction_type_id',
                'payroll_id',
                'fk_remittance_id',
                'payee_id',
                'is_payable',
                'mrd_classification_id',
                'book_id',
            ], 'integer'],
            [['dv_number', 'object_code', 'in_timestamp'], 'string', 'max' => 255],

            [['reporting_period'], 'string', 'max' => 50],
            [['particular', 'dv_link'], 'string'],
            [['recieved_at'], 'safe'],
            [[
                'particular',
                'payee_id',
                'reporting_period',
                'book_id',
                'recieved_at',
                'fk_dv_transaction_type_id',
            ], 'required'],
            [[

                'particular',
                'transaction_type',
            ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],



        ];
    }
    public static function getDetailedDv($year)
    {
        return Yii::$app->db->createCommand("CALL prc_GetDetailedDvs(:yr)")->bindValue(':yr', $year)->queryAll();
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'payee_id' => 'Payee',
            'dv_number' => 'Dv Number',
            'reporting_period' => 'Reporting Period',
            'transaction_begin_time' => 'Transaction Begin Time',
            'in_timestamp' => 'IN Timestamp',
            'tracking_sheet_id' => 'IN Timestamp',
            'is_payable' => 'Is Payable',
            'particular' => 'Particular',
            'mrd_classification_id' => 'MRD Classification',
            'object_code' => 'Object Code',
            'fk_dv_transaction_type_id' => 'Transaction Type',
            'payroll_id' => 'Payroll Number',
            'fk_remittance_id' => 'Remittance Number',
            'book_id' => 'Book',
            'recieved_at' => 'Receive at',
            'nature_of_transaction_id' => 'Nature of Transaction',
            'dv_link' => 'DV Link',
            'liquidation_damages' => 'Liquidation Damage',
            'tax_portion_of_pos' => 'Liquidation ',

        ];
    }

    /**
     * Gets query for [[ProcessOrs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProcessOrs()
    {
        return $this->hasOne(ProcessOrs::class, ['id' => 'process_ors_id']);
    }

    /**
     * Gets query for [[Raoud]].
     *
     * @return \yii\db\ActiveQuery
     */

    public function getDvAucsEntries()
    {
        return $this->hasMany(DvAucsEntries::class, ['dv_aucs_id' => 'id'])
            ->andWhere(['is_deleted' => false]);
    }
    public function getNatureOfTransaction()
    {
        return $this->hasOne(NatureOfTransaction::class, ['id' => 'nature_of_transaction_id']);
    }
    public function getMrdClassification()
    {
        return $this->hasOne(MrdClassification::class, ['id' => 'mrd_classification_id']);
    }
    public function getPayee()
    {
        return $this->hasOne(Payee::class, ['id' => 'payee_id']);
    }
    public function getCashDisbursement()
    {
        return $this->hasOne(CashDisbursement::class, ['dv_aucs_id' => 'id']);
    }
    public function getDvAccountingEntries()
    {
        return $this->hasMany(DvAccountingEntries::class, ['dv_aucs_id' => 'id']);
    }
    public function getTrackingSheet()
    {
        return $this->hasOne(TrackingSheet::class, ['id' => 'tracking_sheet_id']);
    }
    public function getDvAucsFile()
    {
        return $this->hasOne(DvAucsFile::class, ['fk_dv_aucs_id' => 'id']);
    }
    public function getDvTransactionType()
    {
        return $this->hasOne(DvTransactionType::class, ['id' => 'fk_dv_transaction_type_id']);
    }
    public function getOrsBreakdowns()
    {
        return $this->hasMany(DvAucsOrsBreakdown::class, ['fk_dv_aucs_id' => 'id']);
    }


    public  function getDvCeckNum()
    {
        return Yii::$app->db->createCommand("SELECT
            `cash_disbursement`.`check_or_ada_no`
            FROM `cash_disbursement` 
            JOIN `cash_disbursement_items` ON cash_disbursement.id = cash_disbursement_items.fk_cash_disbursement_id
            WHERE `cash_disbursement`.`is_cancelled`=0
            AND `cash_disbursement_items`.`is_deleted`=0
            AND NOT EXISTS (SELECT `c`.`parent_disbursement` 
            FROM `cash_disbursement` `c` 
            WHERE `c`.`is_cancelled`=1 AND `c`.`parent_disbursement`=cash_disbursement.id)
            AND cash_disbursement_items.fk_dv_aucs_id = :id")
            ->bindValue(':id', $this->id)
            ->queryScalar();
    }

    public function getOrsBreakdown()
    {
        return $this->queryOrsBreakdown($this->id);
    }
    // public static function generateOrsBreakdown($id)
    // {
    //     return static::queryOrsBreakdown($id);
    // }
    public function queryOrsBreakdown($id)
    {

        return Yii::$app->db->createCommand("WITH  cte_detailed_ors_entry as (
            SELECT 
            process_ors_entries.*,
            chart_of_accounts.uacs,
            `chart_of_accounts`.`general_ledger`,
            COALESCE(dv_ors_breakdowns.dv_total_breadown,0) as dv_total_breadown,
            COALESCE(process_ors_entries.amount,0) - COALESCE(dv_ors_breakdowns.dv_total_breadown,0) as balance
            FROM process_ors_entries
            LEFT JOIN (SELECT 
            tbl_dv_aucs_ors_breakdown.fk_process_ors_entry_id,
            SUM(
            tbl_dv_aucs_ors_breakdown.amount_disbursed +
            tbl_dv_aucs_ors_breakdown.vat_nonvat +
            tbl_dv_aucs_ors_breakdown.ewt_goods_services +
            tbl_dv_aucs_ors_breakdown.compensation +
            tbl_dv_aucs_ors_breakdown.other_trust_liabilities +
            tbl_dv_aucs_ors_breakdown.liquidation_damage +
            tbl_dv_aucs_ors_breakdown.tax_portion_of_post
            ) as dv_total_breadown
             FROM  tbl_dv_aucs_ors_breakdown
            WHERE tbl_dv_aucs_ors_breakdown.is_deleted  = 0
            AND tbl_dv_aucs_ors_breakdown.fk_dv_aucs_id != :dv_id 
            GROUP BY tbl_dv_aucs_ors_breakdown.fk_process_ors_entry_id
            ) as  dv_ors_breakdowns ON process_ors_entries.id = dv_ors_breakdowns.fk_process_ors_entry_id
            JOIN `chart_of_accounts` ON process_ors_entries.chart_of_account_id = chart_of_accounts.id
            )
            
            
            SELECT  ROUND((dv_aucs_entries.amount_disbursed * cte_detailed_ors_entry.balance)/ors_total.total_ors_amount,2)        AS amount_disbursed
                   ,ROUND((dv_aucs_entries.vat_nonvat * cte_detailed_ors_entry.balance)/ors_total.total_ors_amount,2)              AS vat_nonvat
                   ,ROUND((dv_aucs_entries.ewt_goods_services * cte_detailed_ors_entry.balance)/ors_total.total_ors_amount,2)      AS ewt_goods_services
                   ,ROUND((dv_aucs_entries.compensation * cte_detailed_ors_entry.balance)/ors_total.total_ors_amount,2)            AS compensation
                   ,ROUND((dv_aucs_entries.other_trust_liabilities * cte_detailed_ors_entry.balance)/ors_total.total_ors_amount,2) AS other_trust_liabilities
                   ,ROUND((dv_aucs_entries.liquidation_damage * cte_detailed_ors_entry.balance)/ors_total.total_ors_amount,2)      AS liquidation_damage
                   ,ROUND((dv_aucs_entries.tax_portion_of_post * cte_detailed_ors_entry.balance)/ors_total.total_ors_amount,2)     AS tax_portion_of_post
                   ,`ors_total`.`total_ors_amount`
                   ,`cte_detailed_ors_entry`.`uacs`
                   ,`cte_detailed_ors_entry`.`general_ledger`
                   ,`cte_detailed_ors_entry`.`balance`
                   ,`cte_detailed_ors_entry`.`id`AS `ors_entry_id`
                   ,`process_ors`.`serial_number`AS `ors_number`
                 
            
            FROM `dv_aucs`
            JOIN `dv_aucs_entries` ON dv_aucs.id = dv_aucs_entries.dv_aucs_id
            JOIN `process_ors` ON dv_aucs_entries.process_ors_id = process_ors.id
            JOIN `cte_detailed_ors_entry` ON process_ors.id = cte_detailed_ors_entry.process_ors_id
            
            LEFT JOIN
            (
                SELECT  cte_detailed_ors_entry.process_ors_id
                       ,SUM(cte_detailed_ors_entry.balance) AS total_ors_amount
                FROM cte_detailed_ors_entry
                GROUP BY  cte_detailed_ors_entry.process_ors_id
            ) AS ors_total
            ON process_ors.id = ors_total.process_ors_id
            
            WHERE (`dv_aucs`.`id` = :dv_id)
            AND (`dv_aucs_entries`.`is_deleted` = FALSE)
        ")
            ->bindValue(":dv_id", $id)
            ->queryAll();
    }
    public function getDvItems()
    {
        return DvAucsEntries::find()
            ->addSelect([
                "dv_aucs_entries.id",
                "process_ors.serial_number",
                "`transaction`.particular",
                new Expression("payee.account_name as payee"),
                "dv_aucs_entries.process_ors_id",
                "dv_aucs_entries.amount_disbursed",
                "dv_aucs_entries.vat_nonvat",
                "dv_aucs_entries.ewt_goods_services",
                "dv_aucs_entries.compensation",
                "dv_aucs_entries.other_trust_liabilities",
                "dv_aucs_entries.liquidation_damage",
                "dv_aucs_entries.tax_portion_of_post",
            ])
            ->join("LEFT JOIN", "process_ors", "dv_aucs_entries.process_ors_id = process_ors.id")
            ->join("LEFT JOIN", "`transaction`", "process_ors.transaction_id = `transaction`.id")
            ->join("LEFT JOIN", "payee", "`transaction`.payee_id = payee.id")
            ->andWhere(["dv_aucs_entries.dv_aucs_id" => $this->id])
            ->andWhere(["dv_aucs_entries.is_deleted" => false])
            ->asArray()
            ->all();
    }
    public function insertItems($items)
    {
        try {

            $itemModels = [];
            $deleteItems = $this->deleteItems(ArrayHelper::getColumn($items, 'id'));
            if ($deleteItems !== true) {
                throw new ErrorException($deleteItems);
            }
            foreach ($items as $index => $item) {
                $model = !empty($item['id']) ? DvAucsEntries::findOne($item['id']) : new DvAucsEntries();
                $model->attributes = $item;
                $model->dv_aucs_id = $this->id;
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
    public function getAccountingEntries()
    {

        return DvAccountingEntries::find()
            ->addSelect([
                new Expression("CONCAT(dv_accounting_entries.object_code,'-',accounting_codes.account_title) as account_title"),
                "dv_accounting_entries.object_code",
                new Expression("COALESCE(dv_accounting_entries.debit,0) as debit"),
                new Expression("COALESCE(dv_accounting_entries.credit,0) as credit"),
                "dv_accounting_entries.id"
            ])
            ->join("LEFT JOIN", "accounting_codes", "dv_accounting_entries.object_code = accounting_codes.object_code ")
            ->andWhere([
                "dv_accounting_entries.dv_aucs_id" => $this->id
            ])
            ->asArray()
            ->all();
    }
    public function insertAccountingEntries($items)
    {
        try {


            if (array_sum(array_column($items, 'debit')) !== array_sum(array_column($items, 'credit'))) {
                throw new ErrorException("Debit and Credit is Not Equal");
            }
            $itemModels = [];
            // $deleteItems = $this->deleteItems(ArrayHelper::getColumn($items, 'id'));
            // if ($deleteItems !== true) {
            //     throw new ErrorException($deleteItems);
            // }
            foreach ($items as $index => $item) {
                $model = !empty($item['id']) ? DvAccountingEntries::findOne($item['id']) : new DvAccountingEntries();
                $model->attributes = $item;
                $model->dv_aucs_id = $this->id;
                $itemModels[] = $model;
            }
            foreach ($itemModels as $model) {
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Accounting Entry Model Save Failed');
                }
            };

            return true;
        } catch (ErrorException $e) {
            return $e->getMessage();
        }
    }
    private function deleteItems($items)
    {
        $queryItems  = Yii::$app->db->createCommand("SELECT dv_aucs_entries.id FROM dv_aucs_entries WHERE dv_aucs_id  = :id
        AND is_deleted = 0")
            ->bindValue(':id', $this->id)
            ->queryAll();
        $toDelete = array_diff(array_column($queryItems, 'id'), $items);
        if (!empty($toDelete)) {
            $params = [];
            $sql  = ' AND ';
            $sql .= Yii::$app->db->queryBuilder->buildCondition(['IN', 'id', $toDelete], $params);
            Yii::$app->db->createCommand("UPDATE dv_aucs_entries
                SET dv_aucs_entries.is_deleted = 1 
                WHERE dv_aucs_entries.dv_aucs_id = :id
                AND dv_aucs_entries.is_deleted= 0
                $sql", $params)
                ->bindValue(':id', $this->id)
                ->execute();
        }
        return true;
    }
    public function getAdvances()
    {
        $advances_data = Yii::$app->db->createCommand("SELECT 
        advances.province,
        advances.id,
        advances.reporting_period,
            advances.bank_account_id,
            fk_office_id
                           FROM advances
                           WHERE advances.dv_aucs_id = :dv_id")
            ->bindValue(":dv_id", $this->id)
            ->queryOne();
        $advancesItems = Yii::$app->db->createCommand("SELECT 
                advances_entries.id,
                advances_entries.object_code,
                advances_entries.fund_source,
                advances_entries.reporting_period,
                advances_entries.fk_fund_source_type_id,
                advances_entries.fk_advances_report_type_id,
                advances_entries.amount,
                accounting_codes.account_title
            FROM advances
            LEFT JOIN advances_entries ON advances.id  = advances_entries.advances_id
            LEFT JOIN accounting_codes ON advances_entries.object_code = accounting_codes.object_code
            WHERE advances.dv_aucs_id = :dv_id
            AND advances_entries.is_deleted !=1")
            ->bindValue(":dv_id", $this->id)
            ->queryAll();

        return [
            'advances' => $advances_data,
            'advancesItems' => $advancesItems,

        ];
    }

    public function insertBreakdownItems($items)
    {
        try {
            $itemModels = [];
            $deleteItems = $this->deleteBreakdownItems(ArrayHelper::getColumn($items, 'id'));
            if ($deleteItems !== true) {
                throw new ErrorException($deleteItems);
            }
            foreach ($items as $index => $item) {
                $model = !empty($item['id']) ? DvAucsOrsBreakdown::findOne($item['id']) : new DvAucsOrsBreakdown();
                $model->attributes = $item;
                $model->fk_dv_aucs_id = $this->id;
                $itemModels[] = $model;
            }
            foreach ($itemModels as $model) {
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Accounting Entry Model Save Failed');
                }
            };

            return true;
        } catch (ErrorException $e) {
            return $e->getMessage();
        }
    }
    private function deleteBreakdownItems($items)
    {
        $queryItems  = Yii::$app->db->createCommand("SELECT tbl_dv_aucs_ors_breakdown.id FROM tbl_dv_aucs_ors_breakdown WHERE fk_dv_aucs_id  = :id
        AND is_deleted = 0")
            ->bindValue(':id', $this->id)
            ->queryAll();
        $toDelete = array_diff(array_column($queryItems, 'id'), $items);
        if (!empty($toDelete)) {
            $params = [];
            $sql  = ' AND ';
            $sql .= Yii::$app->db->queryBuilder->buildCondition(['IN', 'id', $toDelete], $params);
            Yii::$app->db->createCommand("UPDATE tbl_dv_aucs_ors_breakdown
                SET tbl_dv_aucs_ors_breakdown.is_deleted = 1 
                WHERE tbl_dv_aucs_ors_breakdown.fk_dv_aucs_id = :id
                AND tbl_dv_aucs_ors_breakdown.is_deleted= 0
                $sql", $params)
                ->bindValue(':id', $this->id)
                ->execute();
        }
        return true;
    }
    public function getBreakdownItems()
    {


        return DvAucsOrsBreakdown::find()
            ->addSelect([
                'tbl_dv_aucs_ors_breakdown.id',
                'tbl_dv_aucs_ors_breakdown.amount_disbursed',
                'tbl_dv_aucs_ors_breakdown.vat_nonvat',
                'tbl_dv_aucs_ors_breakdown.ewt_goods_services',
                'tbl_dv_aucs_ors_breakdown.compensation',
                'tbl_dv_aucs_ors_breakdown.other_trust_liabilities',

                'tbl_dv_aucs_ors_breakdown.liquidation_damage',
                'tbl_dv_aucs_ors_breakdown.tax_portion_of_post',
                "chart_of_accounts.uacs",
                "chart_of_accounts.general_ledger",
                "process_ors_entries.id as ors_entry_id",
                "process_ors.serial_number as ors_number",
                "process_ors_entries.amount",
                "process_ors_entries.serial_number",


            ])

            ->join("JOIN", "process_ors_entries", "tbl_dv_aucs_ors_breakdown.fk_process_ors_entry_id = process_ors_entries.id")
            ->join("JOIN", "process_ors", "process_ors_entries.process_ors_id = process_ors.id")
            ->join("JOIN", "chart_of_accounts", "process_ors_entries.chart_of_account_id = chart_of_accounts.id")
            ->andWhere(['tbl_dv_aucs_ors_breakdown.is_deleted' => false])
            ->andWhere(['tbl_dv_aucs_ors_breakdown.fk_dv_aucs_id' => $this->id])
            ->asArray()
            ->all();
    }
    public static function getDetailedDvs($yr)
    {
        return Yii::$app->db->createCommand("  WITH cte_gd_cash as (
            SELECT
                cash_disbursement_items.fk_dv_aucs_id,
              cash_disbursement.reporting_period,
              cash_disbursement.issuance_date,
              books.`name` as book_name,
              mode_of_payments.`name` as mode_name,
              cash_disbursement.check_or_ada_no,
              cash_disbursement.ada_number
            FROM cash_disbursement
            JOIN cash_disbursement_items ON cash_disbursement.id = cash_disbursement_items.fk_cash_disbursement_id
            LEFT JOIN books ON cash_disbursement.book_id = books.id
            LEFT JOIN mode_of_payments ON cash_disbursement.fk_mode_of_payment_id  = mode_of_payments.id
            WHERE 
            cash_disbursement.is_cancelled = 0
            AND cash_disbursement_items.is_deleted  = 0
            AND NOT EXISTS (SELECT cncl_chks.parent_disbursement
            FROM cash_disbursement as cncl_chks 
            WHERE cncl_chks.parent_disbursement = cash_disbursement.id 
            AND cncl_chks.is_cancelled = 1 
            AND cncl_chks.parent_disbursement IS NOT NULL) 
            )
            SELECT 
                dv_aucs.dv_number,
                dv_aucs.reporting_period,
              cte_gd_cash.check_or_ada_no as check_number,
              cte_gd_cash.ada_number,
                record_allotments.serial_number as allotment_number,
                allotment_chart_of_accounts.uacs as allotment_object_code,
                allotment_chart_of_accounts.general_ledger as allotment_account_title,
              process_ors.serial_number as ors_number,
                ors_chart_of_accounts.uacs as ors_object_code,
                ors_chart_of_accounts.general_ledger as ors_account_title,
                process_ors_entries.serial_number as ors_id,
                process_ors_entries.amount as ors_amount,
              payee.account_name as payee,
              dv_aucs.particular,
              good_ors_breakdowns.amount_disbursed,
              good_ors_breakdowns.vat_nonvat,
              good_ors_breakdowns.ewt_goods_services,
              good_ors_breakdowns.compensation,
              good_ors_breakdowns.other_trust_liabilities,
                good_ors_breakdowns.liquidation_damage,
                good_ors_breakdowns.tax_portion_of_post,
              nature_of_transaction.`name` as nature_of_transaction,
              mrd_classification.`name` as mrd_classification,
              IF(dv_aucs.is_cancelled=1,'Cancelled','Good') as cancelled,
              IF(dv_aucs.is_payable=1,'Yes','No') as payable,
              books.`name` as book_name
            FROM dv_aucs
            LEFT JOIN (SELECT * FROM tbl_dv_aucs_ors_breakdown WHERE  tbl_dv_aucs_ors_breakdown.is_deleted = 0 ) as good_ors_breakdowns ON dv_aucs.id  = good_ors_breakdowns.fk_dv_aucs_id
            LEFT JOIN payee ON dv_aucs.payee_id = payee.id
            LEFT JOIN nature_of_transaction ON dv_aucs.nature_of_transaction_id = nature_of_transaction.id
            LEFT JOIN mrd_classification ON dv_aucs.mrd_classification_id = mrd_classification.id
            LEFT JOIN books ON dv_aucs.book_id = books.id
            LEFT JOIN process_ors_entries ON good_ors_breakdowns.fk_process_ors_entry_id = process_ors_entries.id
            LEFT JOIN process_ors ON process_ors_entries.process_ors_id  = process_ors.id
            LEFT JOIN cte_gd_cash ON dv_aucs.id = cte_gd_cash.fk_dv_aucs_id
            LEFT JOIN record_allotment_entries ON process_ors_entries.record_allotment_entries_id = record_allotment_entries.id
            LEFT JOIN record_allotments ON record_allotment_entries.record_allotment_id = record_allotments.id
            LEFT JOIN chart_of_accounts as allotment_chart_of_accounts ON record_allotment_entries.chart_of_account_id = allotment_chart_of_accounts.id
            LEFT JOIN chart_of_accounts as ors_chart_of_accounts ON process_ors_entries.chart_of_account_id = ors_chart_of_accounts.id
            WHERE dv_aucs.reporting_period LIKE CONCAT(:yr,'%');")
            ->bindValue(":yr", $yr)
            ->queryAll();
    }
}
