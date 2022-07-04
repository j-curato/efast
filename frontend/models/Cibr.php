<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cibr".
 *
 * @property int $id
 * @property string|null $reporting_period
 * @property string|null $province
 * @property string|null $book_name
 */
class Cibr extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cibr';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['reporting_period'], 'string', 'max' => 50],
            [['bank_account_id'], 'integer'],
            [['province', 'book_name', 'serial_number'], 'string', 'max' => 255],
            [[
                'id',
                'serial_number',
                'reporting_period',
                'province',
                'book_name',
                'is_final',
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
            'serial_number' => 'Serial Number',
            'book_name' => 'Book Name',
            'bank_account_id' => 'Bank Account'
        ];
    }
    public function getBankAccount()
    {

        return $this->hasOne(BankAccount::class, ['id' => 'bank_account_id']);
    }
}
