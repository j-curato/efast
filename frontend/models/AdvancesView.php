<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "advances_view".
 *
 * @property int $row_number
 * @property string|null $nft_number
 * @property string|null $dv_number
 * @property string|null $mode_of_payment
 * @property string|null $check_number
 * @property string|null $issuance_date
 * @property string $payee
 * @property string|null $particular
 * @property float|null $amount
 * @property string $book_name
 * @property string|null $province
 * @property string|null $reporting_period
 * @property string|null $fund_source
 * @property string|null $report_type
 * @property string $object_code
 * @property string $account_title
 */
class AdvancesView extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'advances_view';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['row_number'], 'integer'],
            [['particular'], 'string'],
            [['amount'], 'number'],
            [['book_name'], 'required'],
            [['nft_number', 'dv_number', 'payee', 'book_name', 'object_code'], 'string', 'max' => 255],
            [['mode_of_payment', 'issuance_date', 'province', 'report_type'], 'string', 'max' => 50],
            [['check_number'], 'string', 'max' => 100],
            [['reporting_period'], 'string', 'max' => 20],
            [['fund_source', 'account_title'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'row_number' => 'Row Number',
            'nft_number' => 'Nft Number',
            'dv_number' => 'Dv Number',
            'mode_of_payment' => 'Mode Of Payment',
            'check_number' => 'Check Number',
            'issuance_date' => 'Issuance Date',
            'payee' => 'Payee',
            'particular' => 'Particular',
            'amount' => 'Amount',
            'book_name' => 'Book Name',
            'province' => 'Province',
            'reporting_period' => 'Reporting Period',
            'fund_source' => 'Fund Source',
            'report_type' => 'Report Type',
            'object_code' => 'Object Code',
            'account_title' => 'Account Title',
        ];
    }
}
