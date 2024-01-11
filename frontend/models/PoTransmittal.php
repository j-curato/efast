<?php

namespace app\models;

use Yii;
use yii\db\Expression;
use yii\db\Query;

/**
 * This is the model class for table "po_transmittal".
 *
 * @property string $transmittal_number
 * @property string|null $date
 * @property string $created_at
 */
class PoTransmittal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'po_transmittal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'transmittal_number',
                'date',
                'fk_office_id',
                'fk_approved_by'
            ], 'required'],
            [['date', 'created_at'], 'safe'],
            [[
                'fk_office_id',
                'is_accepted',
                'fk_approved_by',
                'fk_officer_in_charge'
            ], 'integer'],
            [['transmittal_number', 'status'], 'string', 'max' => 255],
            [['transmittal_number'], 'unique'],
            [[
                'transmittal_number',
                'date',

            ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'transmittal_number' => 'Transmittal Number',
            'date' => 'Date',
            'created_at' => 'Created At',
            'status' => 'Status',
            'fk_office_id' => 'Office',
            'is_accepted' => 'is Accepted',
            'fk_approved_by' => 'Approved By',
            'fk_officer_in_charge' => 'Officer In-Charge',
        ];
    }
    public function getPoTransmittalEntries()
    {
        return $this->hasMany(PoTransmittalEntries::class, ['po_transmittal_number' => 'transmittal_number']);
    }
    public function getPoTransmittalToCoa()
    {
        return $this->hasOne(PoTransmittalToCoaEntries::class, ['fk_po_transmittal_id' => 'id']);
    }
    public function getApprovedBy()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'fk_approved_by']);
    }
    public function getOfficerInCharge()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'fk_officer_in_charge']);
    }

    private static function queryLiquidationAmountTotals()
    {
        return (new Query())
            ->select([
                'liquidation_entries.liquidation_id',
                'COALESCE(SUM(liquidation_entries.withdrawals),0) as total_withdrawal',
                'COALESCE(SUM(liquidation_entries.expanded_tax),0) as total_expanded_tax',
                'COALESCE(SUM(liquidation_entries.vat_nonvat),0) as total_vat_nonvat',
                'COALESCE(SUM(liquidation_entries.liquidation_damage),0) as total_liquidation_damage',
                'COALESCE(SUM(liquidation_entries.withdrawals),0) +
                COALESCE(SUM(liquidation_entries.expanded_tax),0) +
                COALESCE(SUM(liquidation_entries.vat_nonvat),0) +
                COALESCE(SUM(liquidation_entries.liquidation_damage),0) as gross_amount',
            ])
            ->from('liquidation_entries')
            ->groupBy(['liquidation_entries.liquidation_id']);
    }
    private static function queryUnTransmittedDvs($year)
    {

        return (new Query())
            ->select(['liquidation.id as liquidation_id'])
            ->from('liquidation')
            ->leftJoin(['cte_liquidation_amount_totals' => static::queryLiquidationAmountTotals()], 'liquidation.id = cte_liquidation_amount_totals.liquidation_id')
            ->andWhere([
                'liquidation.is_cancelled' => 0,
            ])
            ->andWhere(
                'liquidation.reporting_period LIKE :year',
                ['year' => $year . '%']
            )
            ->andWhere(['>', 'cte_liquidation_amount_totals.gross_amount', 0])
            ->andWhere(['NOT EXISTS', (new Query())->select('po_transmittal_entries.liquidation_id')->from('po_transmittal_entries')->where(['po_transmittal_entries.is_deleted' => 0, 'po_transmittal_entries.is_returned' => 0, 'po_transmittal_entries.liquidation_id' => new \yii\db\Expression('liquidation.id')])]);
    }
    private  static function queryDvsAtRo()
    {
        return (new Query())
            ->select(['po_transmittal_entries.liquidation_id'])
            ->from('po_transmittal')
            ->join('JOIN', 'po_transmittal_entries', 'po_transmittal.id = po_transmittal_entries.fk_po_transmittal_id')
            ->where([
                'po_transmittal.is_accepted' => 1,
                'po_transmittal_entries.is_returned' => 0,
                'po_transmittal_entries.is_deleted' => 0,
            ])
            ->andWhere([
                'NOT EXISTS',
                (new Query())
                    ->select('po_transmittal_to_coa_entries.fk_po_transmittal_id')
                    ->from('po_transmittal_to_coa_entries')
                    ->where([
                        'po_transmittal_to_coa_entries.is_deleted' => 0,
                        'po_transmittal_to_coa_entries.fk_po_transmittal_id' => new \yii\db\Expression('po_transmittal.id')
                    ])
            ])
            ->groupBy(['po_transmittal_entries.liquidation_id']);
    }
    private static function queryDvsPendingAtRo()
    {
        return (new Query())
            ->select(['po_transmittal_entries.liquidation_id'])
            ->from('po_transmittal')
            ->join('JOIN', 'po_transmittal_entries', 'po_transmittal.id = po_transmittal_entries.fk_po_transmittal_id')
            ->where([
                'po_transmittal.is_accepted' => 0,
                'po_transmittal_entries.is_returned' => 0,
                'po_transmittal_entries.is_deleted' => 0,
            ])
            ->andWhere([
                'NOT EXISTS',
                (new Query())->select('po_transmittal_to_coa_entries.fk_po_transmittal_id')
                    ->from('po_transmittal_to_coa_entries')
                    ->where([
                        'po_transmittal_to_coa_entries.is_deleted' => 0,
                        'po_transmittal_to_coa_entries.fk_po_transmittal_id' => new \yii\db\Expression('po_transmittal.id')
                    ])
            ])
            ->groupBy(['po_transmittal_entries.liquidation_id']);
    }
    private static function queryDvsAtCoa()
    {
        return (new Query())
            ->select(['po_transmittal_entries.liquidation_id'])
            ->from('po_transmittal_to_coa_entries')
            ->join('JOIN', 'po_transmittal', 'po_transmittal_to_coa_entries.fk_po_transmittal_id = po_transmittal.id')
            ->join('JOIN', 'po_transmittal_entries', 'po_transmittal.id = po_transmittal_entries.fk_po_transmittal_id')
            ->where(['po_transmittal_to_coa_entries.is_deleted' => 0, 'po_transmittal_entries.is_deleted' => 0, 'po_transmittal_entries.is_returned' => 0])
            ->groupBy(['po_transmittal_entries.liquidation_id']);
    }
    public static function getMonthlyTransmittalCountByYear($year, $office_id)
    {
        return (new Query())
            ->select([
                'office.office_name',
                'liquidation.reporting_period',
                'COUNT(liquidation.id) as total_dvs',
                'COUNT(cte_untransmitted_dvs.liquidation_id) as total_untransmitted_dvs',
                'COUNT(cte_dvs_pending_at_ro.liquidation_id) as total_dvs_pending_at_ro',
                'COUNT(cte_dvs_at_ro.liquidation_id) as total_dvs_at_ro',
                'COUNT(cte_dvs_at_coa.liquidation_id) as total_dvs_at_coa',
            ])
            ->from('liquidation')
            ->leftJoin('check_range', 'liquidation.check_range_id = check_range.id')
            ->leftJoin('bank_account', 'check_range.bank_account_id = bank_account.id')
            ->leftJoin('office', 'bank_account.fk_office_id = office.id')
            ->leftJoin(['cte_liquidation_amount_totals' => static::queryLiquidationAmountTotals()], 'liquidation.id = cte_liquidation_amount_totals.liquidation_id')
            ->leftJoin(['cte_dvs_pending_at_ro' => static::queryDvsPendingAtRo()], 'liquidation.id = cte_dvs_pending_at_ro.liquidation_id')
            ->leftJoin(['cte_dvs_at_ro' =>  static::queryDvsAtRo()], 'liquidation.id = cte_dvs_at_ro.liquidation_id')
            ->leftJoin(['cte_dvs_at_coa' => static::queryDvsAtCoa()], 'liquidation.id = cte_dvs_at_coa.liquidation_id')
            ->leftJoin(['cte_untransmitted_dvs' => static::queryUnTransmittedDvs($year)], 'liquidation.id = cte_untransmitted_dvs.liquidation_id')
            ->andWhere([
                'liquidation.is_cancelled' => 0,
            ])
            ->andWhere(['office.id' => $office_id])
            ->andWhere('cte_liquidation_amount_totals.gross_amount >0')
            ->andWhere('liquidation.reporting_period LIKE :year', ['year' => $year . '%'])

            ->groupBy(['office.office_name', 'liquidation.reporting_period'])
            ->orderBy(['office.office_name' => SORT_ASC])
            ->createCommand()->queryAll();
    }
    public static function getMonthlyTransmittalListByYear($year, $office_id)
    {
        return (new Query())
            ->select([
                'office.office_name',
                'liquidation.id',
                'liquidation.reporting_period',
                'liquidation.dv_number',
                'liquidation.check_number',
                'cte_liquidation_amount_totals.gross_amount',
                'po_transaction.particular',
                'po_transaction.payee',
                new Expression('cte_untransmitted_dvs.liquidation_id as untransmitted'),
                new Expression('cte_dvs_pending_at_ro.liquidation_id as pending_at_ro'),
                new Expression('cte_dvs_at_ro.liquidation_id as dvs_at_ro'),

            ])
            ->from('liquidation')
            ->join('LEFT JOIN', 'po_transaction', 'liquidation.po_transaction_id = po_transaction.id')
            ->leftJoin('check_range', 'liquidation.check_range_id = check_range.id')
            ->leftJoin('bank_account', 'check_range.bank_account_id = bank_account.id')
            ->leftJoin('office', 'bank_account.fk_office_id = office.id')
            ->leftJoin(['cte_liquidation_amount_totals' => static::queryLiquidationAmountTotals()], 'liquidation.id = cte_liquidation_amount_totals.liquidation_id')
            ->leftJoin(['cte_dvs_pending_at_ro' => static::queryDvsPendingAtRo()], 'liquidation.id = cte_dvs_pending_at_ro.liquidation_id')
            ->leftJoin(['cte_dvs_at_ro' =>  static::queryDvsAtRo()], 'liquidation.id = cte_dvs_at_ro.liquidation_id')
            ->leftJoin(['cte_untransmitted_dvs' => static::queryUnTransmittedDvs($year)], 'liquidation.id = cte_untransmitted_dvs.liquidation_id')
            ->andWhere([
                'liquidation.is_cancelled' => 0,
            ])

            ->andWhere(['office.id' => $office_id])
            ->andWhere('cte_liquidation_amount_totals.gross_amount >0')
            ->andWhere('liquidation.reporting_period LIKE :year', ['year' => $year . '%'])

            ->andWhere([
                'OR',
                ['IS NOT ', 'cte_dvs_pending_at_ro.liquidation_id', null],
                ['IS NOT ', 'cte_dvs_at_ro.liquidation_id', null],
                ['IS NOT ', 'cte_untransmitted_dvs.liquidation_id', null],

            ])
            ->orderBy(['office.office_name' => SORT_ASC])
            ->createCommand()->queryAll();
    }
}
