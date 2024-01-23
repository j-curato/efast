<?php

namespace app\models;

use Yii;
use app\behaviors\HistoryLogsBehavior;

/**
 * This is the model class for table "jev_accounting_entries".
 *
 * @property int $id
 * @property int $jev_preparation_id
 * @property int $chart_of_account_id
 * @property float $debit
 * @property float $credit
 *
 * @property ChartOfAccounts $chartOfAccount
 * @property JevPreparation $jevPreparation
 */
class JevAccountingEntries extends \yii\db\ActiveRecord
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
        return 'jev_accounting_entries';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [

            [['jev_preparation_id', 'chart_of_account_id'], 'integer'],
            [['current_noncurrent'], 'string'],
            [['debit', 'credit'], 'number'],
            [['object_code'], 'string', 'max' => 255],
            [['object_code'], 'filter', 'filter' => "\yii\helpers\HtmlPurifier::process"],
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
            [['jev_preparation_id'], 'exist', 'skipOnError' => true, 'targetClass' => JevPreparation::class, 'targetAttribute' => ['jev_preparation_id' => 'id']],
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
            'chart_of_account_id' => 'Chart Of Account ID',
            'debit' => 'Debit',
            'credit' => 'Credit',
            'current_noncurrent' => 'Current/NonCurrent',
        ];
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
     * Gets query for [[JevPreparation]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJevPreparation()
    {
        return $this->hasOne(JevPreparation::class, ['id' => 'jev_preparation_id']);
    }
}
