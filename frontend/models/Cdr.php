<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cdr".
 *
 * @property int $id
 * @property string|null $serial_number
 * @property string|null $reporting_period
 * @property string|null $province
 * @property string|null $book_name
 * @property string|null $report_type
 * @property int|null $is_final
 */
class Cdr extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cdr';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['is_final'], 'integer'],
            [['fk_bank_account_id'], 'number'],
            [['serial_number'], 'string', 'max' => 100],
            [['reporting_period', 'province', 'book_name'], 'string', 'max' => 50],
            [['report_type'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'serial_number' => 'Serial Number',
            'reporting_period' => 'Reporting Period',
            'province' => 'Province',
            'book_name' => 'Book Name',
            'report_type' => 'Report Type',
            'is_final' => 'Is Final',
            'fk_bank_account_id' => 'Bank Account ',
        ];
    }
    public function getBook()
    {
        return $this->hasOne(Books::class, ['id' => 'book_name']);
    }
    public function getBankAccount()
    {
        return $this->hasOne(BankAccount::class, ['id' => 'fk_bank_account_id']);
    }
}
