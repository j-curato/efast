<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%jev_accounting_entries}}".
 *
 * @property int $id
 * @property int|null $jev_preparation_id
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
 * @property JevPreparation $jevPreparation
 * @property NetAssetEquity $netAssetEquity
 */
class JevAccountingEntries extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%jev_accounting_entries}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['jev_preparation_id', 'cashflow_id', 'net_asset_equity_id', 'chart_of_account_id', 'lvl'], 'integer'],
            [['debit', 'credit'], 'number'],
            [['closing_nonclosing'], 'string', 'max' => 50],
            [['current_noncurrent', 'object_code'], 'string', 'max' => 255],
            [[

                'id',
                'jev_preparation_id',
                'cashflow_id',
                'net_asset_equity_id',
                'chart_of_account_id',
                'debit',
                'credit',
                'closing_nonclosing',
                'current_noncurrent',
                'lvl',
                'object_code',
            ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
            [['cashflow_id'], 'exist', 'skipOnError' => true, 'targetClass' => CashFlow::class, 'targetAttribute' => ['cashflow_id' => 'id']],
            [['chart_of_account_id'], 'exist', 'skipOnError' => true, 'targetClass' => ChartOfAccounts::class, 'targetAttribute' => ['chart_of_account_id' => 'id']],
            [['jev_preparation_id'], 'exist', 'skipOnError' => true, 'targetClass' => JevPreparation::class, 'targetAttribute' => ['jev_preparation_id' => 'id']],
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
            'jev_preparation_id' => 'Jev Preparation ID',
            'cashflow_id' => 'Cashflow ID',
            'net_asset_equity_id' => 'Net Asset Equity ID',
            'chart_of_account_id' => 'Chart Of Account ID',
            'debit' => 'Debit',
            'credit' => 'Credit',
            'closing_nonclosing' => 'Closing Nonclosing',
            'current_noncurrent' => 'Current Noncurrent',
            'lvl' => 'Lvl',
            'object_code' => 'Object Code',
        ];
    }

    /**
     * Gets query for [[Cashflow]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\CashFlowQuery
     */
    public function getCashflow()
    {
        return $this->hasOne(CashFlow::class, ['id' => 'cashflow_id']);
    }

    /**
     * Gets query for [[ChartOfAccount]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\ChartOfAccountsQuery
     */
    public function getChartOfAccount()
    {
        return $this->hasOne(ChartOfAccounts::class, ['id' => 'chart_of_account_id']);
    }

    /**
     * Gets query for [[JevPreparation]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\JevPreparationQuery
     */
    public function getJevPreparation()
    {
        return $this->hasOne(JevPreparation::class, ['id' => 'jev_preparation_id']);
    }

    /**
     * Gets query for [[NetAssetEquity]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\NetAssetEquityQuery
     */
    public function getNetAssetEquity()
    {
        return $this->hasOne(NetAssetEquity::class, ['id' => 'net_asset_equity_id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\JevAccountingEntriesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\JevAccountingEntriesQuery(get_called_class());
    }
}
