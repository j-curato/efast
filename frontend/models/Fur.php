<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "fur".
 *
 * @property int $id
 * @property string|null $reporting_period
 * @property string|null $province
 * @property string $created_at
 */
class Fur extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fur';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'bank_account_id'], 'safe'],
            [['reporting_period'], 'string', 'max' => 50],
            [['province'], 'string', 'max' => 20],
            [[
                'id',
                'reporting_period',
                'province',
                'created_at',
                'document_link',
                'bank_account_id',

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
            'reporting_period' => 'Reporting Period',
            'province' => 'Province',
            'created_at' => 'Created At',
            'bank_account_id' => 'Bank Account',

        ];
    }
    public function getBankAccount()
    {
        return $this->hasOne(BankAccount::class, ['id' => 'bank_account_id']);
    }
}
