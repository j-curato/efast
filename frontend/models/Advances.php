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
            [['bank_account_id', 'reporting_period'], 'required'],
            [['bank_account_id'], 'integer'],

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
            'reporting_period' => 'Reporting Period',
            'nft_number' => 'NFT Number',
            'created_at' => 'Created At',
            'bank_account_id' => 'Bank Account',
            'dv_aucs_id' => 'Dv Aucs',
        ];
    }

    public function getAdvancesEntries()
    {
        return $this->hasMany(AdvancesEntries::class, ['advances_id' => 'id']);
    }
}
