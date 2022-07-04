<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "advances_entries".
 *
 * @property int $id
 * @property int|null $advances_id
 * @property int|null $cash_disbursement_id
 * @property int|null $sub_account1_id
 * @property float|null $amount
 *
 * @property Advances $advances
 * @property CashDisbursement $cashDisbursement
 * @property SubAccounts1 $subAccount1
 */
class AdvancesEntries extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'advances_entries';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['advances_id', 'cash_disbursement_id', 'sub_account1_id', 'is_deleted'], 'integer'],
            [['amount'], 'number'],
            [['reporting_period'], 'required'],

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
            [['advances_id'], 'exist', 'skipOnError' => true, 'targetClass' => Advances::class, 'targetAttribute' => ['advances_id' => 'id']],
            [['cash_disbursement_id'], 'exist', 'skipOnError' => true, 'targetClass' => CashDisbursement::class, 'targetAttribute' => ['cash_disbursement_id' => 'id']],
            [['sub_account1_id'], 'exist', 'skipOnError' => true, 'targetClass' => SubAccounts1::class, 'targetAttribute' => ['sub_account1_id' => 'id']],
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
            'reporting_period' => 'Reporting Period',
            'is_deleted' => 'Is Deleted',
            'object_code' => 'Object Code',
            'fund_source' => 'Fund Source',
            'book_id' => 'Book',
            'fund_source_type' => 'Fund Source Type',
            'division' => 'Division',
            'advances_type' => 'Advances Type',
            'report_type' => 'Report Type',
        ];
    }

    /**
     * Gets query for [[Advances]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAdvances()
    {
        return $this->hasOne(Advances::class, ['id' => 'advances_id']);
    }

    /**
     * Gets query for [[CashDisbursement]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCashDisbursement()
    {
        return $this->hasOne(CashDisbursement::class, ['id' => 'cash_disbursement_id']);
    }

    /**
     * Gets query for [[SubAccount1]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubAccount1()
    {
        return $this->hasOne(SubAccounts1::class, ['id' => 'sub_account1_id']);
    }
    public function getLiquidation()
    {
        return $this->hasOne(SubAccounts1::class, ['id' => 'sub_account1_id']);
    }
    public function getAccountingCode()
    {
        return $this->hasOne(AccountingCodes::class, ['object_code' => 'object_code']);
    }
}
