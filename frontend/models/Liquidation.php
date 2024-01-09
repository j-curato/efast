<?php

namespace app\models;

use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "liquidation".
 *
 * @property int $id
 * @property int|null $payee_id
 * @property int|null $responsibility_center_id
 * @property string|null $check_date
 * @property string|null $check_number
 * @property string|null $dv_number
 * @property string|null $particular
 *
 * @property Payee $payee
 * @property ResponsibilityCenter $responsibilityCenter
 * @property LiquidationEntries[] $liquidationEntries
 */
class Liquidation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'liquidation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'payee_id', 'responsibility_center_id', 'po_transaction_id', 'check_range_id',
                'fk_certified_by',
                'fk_approved_by'
            ], 'integer'],
            [['particular'], 'string'],
            [['check_date',  'province', 'cancel_reporting_period'], 'string', 'max' => 100],
            [['reporting_period'], 'string', 'max' => 20],
            [[
                'reporting_period',
                'check_date',
                'check_number',
                'check_range_id', 
                // 'fk_certified_by',
                // 'fk_approved_by'
            ], 'required'],
            [['check_range_id',], 'required', 'when' => function ($model) {
                return strtotime($model->reporting_period) > strtotime('2021-10');
            }],
            [['dv_number', 'created_at'], 'string', 'max' => 100],
            [['payee_id'], 'exist', 'skipOnError' => true, 'targetClass' => Payee::class, 'targetAttribute' => ['payee_id' => 'id']],
            [['responsibility_center_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResponsibilityCenter::class, 'targetAttribute' => ['responsibility_center_id' => 'id']],
            [['po_transaction_id'], 'exist', 'skipOnError' => true, 'targetClass' => PoTransaction::class, 'targetAttribute' => ['po_transaction_id' => 'id']],
            // [[
            //     'check_date',
            //     'check_number',
            //     'dv_number',

            //     'reporting_period',
            //     'province',
            //     'cancel_reporting_period',
            // ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
            [[
                'fk_certified_by',
                'fk_approved_by'
            ], 'required', 'when' => function ($model) {

                return $model->is_cancelled == false ? true : false;
            }],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'payee_id' => 'Payee ID',
            'responsibility_center_id' => 'Responsibility Center ',
            'check_date' => 'Check Date',
            'check_number' => 'Check Number',
            'dv_number' => 'Dv Number',
            'particular' => 'Particular',
            'reporting_period' => 'Reporting Period',
            'po_transaction_id' => 'Transaction',
            'province' => 'Province',
            'payee' => 'Payee',
            'check_range_id' => 'Check Range',
            'cancel_reporting_period' => 'Cancel Reporting Period',
            'created_at' => 'Created At',
            'fk_certified_by' => 'Certified By',
            'fk_approved_by' => 'Approved By',
        ];
    }

    /**
     * Gets query for [[Payee]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPayee()
    {
        return $this->hasOne(Payee::class, ['id' => 'payee_id']);
    }

    /**
     * Gets query for [[ResponsibilityCenter]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResponsibilityCenter()
    {
        return $this->hasOne(ResponsibilityCenter::class, ['id' => 'responsibility_center_id']);
    }
    public function getPoTransaction()
    {
        return $this->hasOne(PoTransaction::class, ['id' => 'po_transaction_id']);
    }

    /**
     * Gets query for [[LiquidationEntries]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLiquidationEntries()
    {
        return $this->hasMany(LiquidationEntries::class, ['liquidation_id' => 'id']);
    }
    public function getCheckRange()
    {
        return $this->hasOne(CheckRange::class, ['id' => 'check_range_id']);
    }
    public function getApprovedBy()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'fk_approved_by']);
    }
    public function getCertifiedBy()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'fk_certified_by']);
    }
    public function getTotalAmounts()
    {
        return LiquidationEntries::find()
            ->addSelect([
                new Expression("COALESCE(SUM(liquidation_entries.withdrawals),0) as total_withdrawal"),
                new Expression("COALESCE(SUM(liquidation_entries.vat_nonvat),0) as total_vat"),
                new Expression("COALESCE(SUM(liquidation_entries.expanded_tax),0) as total_expanded"),
                new Expression("COALESCE(SUM(liquidation_entries.liquidation_damage),0) as total_liquidation_damage")
            ])
            ->andWhere(['liquidation_entries.liquidation_id' => $this->id])
            ->asArray()
            ->one();
    }
    public function getDvDebitCredits()
    {
        return Yii::$app->db->createCommand("WITH cte_credits as (
                SELECT 
                advances_entries.object_code,
                0 as debit,
                SUM(liquidation_entries.withdrawals) as credit
                FROM  liquidation_entries 
                LEFT JOIN advances_entries ON liquidation_entries.advances_entries_id =advances_entries.id
                WHERE liquidation_entries.liquidation_id = :liquidation_id
                GROUP BY advances_entries.object_code
                UNION ALL
                SELECT 
                uacs_code.uacs as object_code,
                0 as debit,
                COALESCE(SUM(liquidation_entries.liquidation_damage),0) as credit
                FROM liquidation_entries
                JOIN (SELECT * FROM chart_of_accounts WHERE chart_of_accounts.uacs = 4020199099) as uacs_code
                WHERE liquidation_entries.liquidation_id = :liquidation_id
                ),
                cte_debits as (
                SELECT 
                (CASE 
                WHEN liquidation_entries.new_object_code IS NOT NULL THEN  liquidation_entries.new_object_code
                WHEN liquidation_entries.new_chart_of_account_id IS NOT NULL THEN chart_of_account2.uacs
                ELSE chart_of_account1.uacs
                END) as object_code,
                
                COALESCE(SUM(liquidation_entries.withdrawals),0)+
                COALESCE(SUM(liquidation_entries.vat_nonvat),0)+
                COALESCE(SUM(liquidation_entries.expanded_tax),0)+
                COALESCE(SUM( liquidation_entries.liquidation_damage),0) as debit,
                0 as credit
                FROM  liquidation_entries
                LEFT JOIN chart_of_accounts as  chart_of_account1   ON liquidation_entries.chart_of_account_id = chart_of_account1.id
                LEFT JOIN chart_of_accounts  as chart_of_account2 ON liquidation_entries.new_chart_of_account_id = chart_of_account2.id
                WHERE liquidation_entries.liquidation_id = :liquidation_id
                GROUP BY object_code
                
                )
                
                SELECT 
                x.*,
                (CASE 
                WHEN CountUnderscores(x.object_code) = 0 THEN (SELECT chart_of_accounts.`general_ledger` FROM chart_of_accounts WHERE chart_of_accounts.uacs =x.object_code )
                WHEN CountUnderscores(x.object_code) = 1 THEN (SELECT sub_accounts1.`name` FROM sub_accounts1 WHERE sub_accounts1.object_code =x.object_code )
                WHEN CountUnderscores(x.object_code) = 2 THEN (SELECT sub_accounts2.`name` FROM sub_accounts2 WHERE sub_accounts2.object_code =x.object_code )
                ELSE ''
                END) as account_title
                FROM (
                SELECT * FROM cte_credits WHERE cte_credits.debit + cte_credits.credit  !=0
                UNION 
                SELECT * FROM cte_debits) as x ORDER BY x.debit DESC ,x.credit DESC")
            ->bindValue(':liquidation_id', $this->id)
            ->queryAll();
    }
    public function getVatAccountByProvince()
    {

        return SubAccounts1::find()
            ->addSelect([
                'object_code',
                new Expression("sub_accounts1.name as account_title")
            ])
            ->andWhere([
                "sub_accounts1.`name`" => 'Due to BIR - ' . $this->province . ' 1600'
            ])
            ->asArray()
            ->one();
    }
    public function getExpandedAccountByProvince()
    {

        return SubAccounts1::find()
            ->addSelect([
                'object_code',
                new Expression("sub_accounts1.name as account_title")
            ])
            ->andWhere([
                "sub_accounts1.`name`" => 'Due to BIR - ' . $this->province . ' 1601E'
            ])
            ->asArray()
            ->one();
    }
}
