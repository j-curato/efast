<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%advances_entries}}".
 *
 * @property int $id
 * @property int|null $advances_id
 * @property int|null $cash_disbursement_id
 * @property int|null $sub_account1_id
 * @property float|null $amount
 * @property string|null $object_code
 * @property string|null $fund_source
 * @property int|null $book_id
 * @property string|null $reporting_period
 * @property string|null $fund_source_type
 * @property string|null $division
 * @property string|null $advances_type
 * @property string|null $report_type
 * @property int|null $is_deleted
 *
 * @property Advances $advances
 * @property CashDisbursement $cashDisbursement
 * @property SubAccounts1 $subAccount1
 * @property LiquidationEntries[] $liquidationEntries
 */
class AdvancesEntries extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%advances_entries}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['advances_id', 'cash_disbursement_id', 'sub_account1_id', 'book_id', 'is_deleted'], 'integer'],
            [['amount'], 'number'],
            [['fund_source'], 'string'],
            [['object_code'], 'string', 'max' => 100],
            [['reporting_period'], 'string', 'max' => 50],
            [['fund_source_type', 'division', 'advances_type', 'report_type'], 'string', 'max' => 255],
            [['advances_id'], 'exist', 'skipOnError' => true, 'targetClass' => Advances::class, 'targetAttribute' => ['advances_id' => 'id']],
            [['cash_disbursement_id'], 'exist', 'skipOnError' => true, 'targetClass' => CashDisbursement::class, 'targetAttribute' => ['cash_disbursement_id' => 'id']],
            [['sub_account1_id'], 'exist', 'skipOnError' => true, 'targetClass' => SubAccounts1::class, 'targetAttribute' => ['sub_account1_id' => 'id']],
            [[
                'id',
                'advances_id',
                'cash_disbursement_id',
                'sub_account1_id',
                'amount',
                'object_code',
                'fund_source',
                'book_id',
                'reporting_period',
                'fund_source_type',
                'division',
                'advances_type',
                'report_type',
                'is_deleted',
            ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'advances_id' => 'Advances ID',
            'cash_disbursement_id' => 'Cash Disbursement ID',
            'sub_account1_id' => 'Sub Account1 ID',
            'amount' => 'Amount',
            'object_code' => 'Object Code',
            'fund_source' => 'Fund Source',
            'book_id' => 'Book ID',
            'reporting_period' => 'Reporting Period',
            'fund_source_type' => 'Fund Source Type',
            'division' => 'Division',
            'advances_type' => 'Advances Type',
            'report_type' => 'Report Type',
            'is_deleted' => 'Is Deleted',
        ];
    }

    /**
     * Gets query for [[Advances]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\AdvancesQuery
     */
    public function getAdvances()
    {
        return $this->hasOne(Advances::class, ['id' => 'advances_id']);
    }

    /**
     * Gets query for [[CashDisbursement]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\CashDisbursementQuery
     */
    public function getCashDisbursement()
    {
        return $this->hasOne(CashDisbursement::class, ['id' => 'cash_disbursement_id']);
    }

    /**
     * Gets query for [[SubAccount1]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\SubAccounts1Query
     */
    public function getSubAccount1()
    {
        return $this->hasOne(SubAccounts1::class, ['id' => 'sub_account1_id']);
    }

    /**
     * Gets query for [[LiquidationEntries]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\LiquidationEntriesQuery
     */
    // public function getLiquidationEntries()
    // {
    //     return $this->hasMany(LiquidationEntries::class, ['advances_entries_id' => 'id']);
    // }

    /**
     * {@inheritdoc}
     * @return \common\models\query\AdvancesEntriesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\AdvancesEntriesQuery(get_called_class());
    }
}
