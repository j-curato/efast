<?php

namespace app\models;

use Yii;
use app\behaviors\HistoryLogsBehavior;

/**
 * This is the model class for table "dv_accounting_entries".
 *
 * @property int $id
 * @property int|null $dv_aucs_id
 * @property int|null $cashflow_id
 * @property int|null $net_asset_equity_id
 * @property int|null $chart_of_account_id
 * @property float|null $debit
 * @property float|null $credit
 * @property string|null $closing_nonclosing
 * @property string|null $current_noncurrent
 * @property int|null $lvl
 * @property string|null $object_code
 *
 * @property CashFlow $cashflow
 * @property ChartOfAccounts $chartOfAccount
 * @property DvAucs $dvAucs
 * @property NetAssetEquity $netAssetEquity
 */
class DvAccountingEntries extends \yii\db\ActiveRecord
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
        return 'dv_accounting_entries';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dv_aucs_id', 'cashflow_id', 'net_asset_equity_id', 'chart_of_account_id',], 'integer'],
            [['debit', 'credit'], 'number'],
            [['closing_nonclosing'], 'string', 'max' => 50],
            [['current_noncurrent', 'object_code'], 'string', 'max' => 255],
            [[
                'id',
                'dv_aucs_id',
                'cashflow_id',
                'net_asset_equity_id',
                'chart_of_account_id',
                'debit',
                'credit',
                'closing_nonclosing',
                'current_noncurrent',
                'object_code',
                'payroll_id',
                'remittance_payee_id',

            ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
            [['cashflow_id'], 'exist', 'skipOnError' => true, 'targetClass' => CashFlow::class, 'targetAttribute' => ['cashflow_id' => 'id']],
            [['chart_of_account_id'], 'exist', 'skipOnError' => true, 'targetClass' => ChartOfAccounts::class, 'targetAttribute' => ['chart_of_account_id' => 'id']],
            [['dv_aucs_id'], 'exist', 'skipOnError' => true, 'targetClass' => DvAucs::class, 'targetAttribute' => ['dv_aucs_id' => 'id']],
            [['net_asset_equity_id'], 'exist', 'skipOnError' => true, 'targetClass' => NetAssetEquity::class, 'targetAttribute' => ['net_asset_equity_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dv_aucs_id' => 'Dv Aucs ID',
            'cashflow_id' => 'Cashflow ID',
            'net_asset_equity_id' => 'Net Asset Equity ID',
            'chart_of_account_id' => 'Chart Of Account ID',
            'debit' => 'Debit',
            'credit' => 'Credit',
            'closing_nonclosing' => 'Closing Nonclosing',
            'current_noncurrent' => 'Current Noncurrent',
            'object_code' => 'Object Code',
        ];
    }

    /**
     * Gets query for [[Cashflow]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCashflow()
    {
        return $this->hasOne(CashFlow::class, ['id' => 'cashflow_id']);
    }

    /**
     * Gets query for [[ChartOfAccount]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getChartOfAccount()
    {
        return $this->hasOne(ChartOfAccounts::class, ['id' => 'chart_of_account_id']);
    }

    /**
     * Gets query for [[DvAucs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDvAucs()
    {
        return $this->hasOne(DvAucs::class, ['id' => 'dv_aucs_id']);
    }

    /**
     * Gets query for [[NetAssetEquity]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNetAssetEquity()
    {
        return $this->hasOne(NetAssetEquity::class, ['id' => 'net_asset_equity_id']);
    }
}
