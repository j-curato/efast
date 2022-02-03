<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "check_range".
 *
 * @property int $id
 * @property int|null $from
 * @property int|null $to
 */
class CheckRange extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'check_range';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['from', 'to', 'begin_balance'], 'integer'],
            [['bank_account_id'], 'safe'],
            [['from', 'to', 'reporting_period'], 'required'],
            [['province', 'reporting_period'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'from' => 'From',
            'to' => 'To',
            'province' => 'Province',
            'bank_account_id' => 'Bank Account',
            'reporting_period' => 'Reporting Period',
            'begin_balance' => 'Begin Balance',
        ];
    }
    public function getBankAccount()
    {
        return $this->hasOne(BankAccount::class, ['id' => 'bank_account_id']);
    }
}
