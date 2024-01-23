<?php

namespace app\models;

use Yii;
use app\behaviors\HistoryLogsBehavior;

/**
 * This is the model class for table "advances_cash_disbursement".
 *
 * @property int $id
 * @property string $book_name
 * @property string|null $mode_of_payment
 * @property string|null $check_or_ada_no
 * @property string|null $ada_number
 * @property string|null $issuance_date
 * @property string|null $dv_number
 * @property string $payee
 * @property string|null $particular
 * @property float|null $total_amount_disbursed
 */
class AdvancesCashDisbursement extends \yii\db\ActiveRecord
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
        return 'advances_cash_disbursement';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['book_name'], 'required'],
            [['particular'], 'string'],
            [['total_amount_disbursed'], 'number'],
            [['book_name', 'dv_number', 'payee'], 'string', 'max' => 255],
            [['mode_of_payment', 'issuance_date'], 'string', 'max' => 50],
            [['check_or_ada_no'], 'string', 'max' => 100],
            [['ada_number'], 'string', 'max' => 40],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'book_name' => 'Book Name',
            'mode_of_payment' => 'Mode Of Payment',
            'check_or_ada_no' => 'Check Or Ada No',
            'ada_number' => 'Ada Number',
            'issuance_date' => 'Issuance Date',
            'dv_number' => 'Dv Number',
            'payee' => 'Payee',
            'particular' => 'Particular',
            'total_amount_disbursed' => 'Total Amount Disbursed',
        ];
    }
}
