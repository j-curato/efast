<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "advances".
 *
 * @property int $id
 * @property int|null $sub_account1_id
 * @property string|null $province
 * @property string|null $report_type
 * @property string|null $particular
 *
 * @property CashDisbursement $cashDisbursement
 * @property SubAccounts1 $subAccount1
 */
class Advances extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'advances';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['province', 'report_type', 'reporting_period'], 'string', 'max' => 50],
            [['particular'], 'string', 'max' => 500],
            [['province', 'reporting_period'], 'required'],
            [[
                'id',
                'province',
                'report_type',
                'particular',
                'nft_number',
                'created_at',
                'reporting_period',
                'book_id',
                'advances_type',
                'bank_account_id',
                'dv_aucs_id',

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
            'province' => 'Province',
            'report_type' => 'Report Type',
            'particular' => 'Particular',
            'reporting_period' => 'Reporting Period',
            'nft_number' => 'NFT Number',
            'created_at' => 'Created At',
            'book_id' => 'Book',
            'advances_type' => 'Advances Type',
            'bank_account_id' => 'Bank Account',
            'dv_aucs_id' => 'Dv Aucs',
        ];
    }

    public function getAdvancesEntries()
    {
        return $this->hasMany(AdvancesEntries::class, ['advances_id' => 'id']);
    }
}
