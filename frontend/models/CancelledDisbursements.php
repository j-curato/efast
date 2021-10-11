<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cancelled_disbursements".
 *
 * @property int $id
 * @property int|null $book_id
 * @property int|null $dv_aucs_id
 * @property string|null $reporting_period
 * @property string|null $mode_of_payment
 * @property string|null $check_or_ada_no
 * @property int $is_cancelled
 * @property string|null $issuance_date
 * @property string|null $ada_number
 * @property string|null $begin_time
 * @property string|null $out_time
 * @property int|null $parent_disbursement
 */
class CancelledDisbursements extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cancelled_disbursements';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'book_id', 'dv_aucs_id', 'is_cancelled', 'parent_disbursement'], 'integer'],
            [['begin_time', 'out_time'], 'safe'],
            [['reporting_period', 'mode_of_payment', 'issuance_date'], 'string', 'max' => 50],
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
            'dv_number' => 'Dv Number',
            'reporting_period' => 'Reporting Period',
            'mode_of_payment' => 'Mode Of Payment',
            'check_or_ada_no' => 'Check Or Ada No',
            'is_cancelled' => 'Is Cancelled',
            'issuance_date' => 'Issuance Date',
            'ada_number' => 'Ada Number',
            'parent_disbursement' => 'Parent Disbursement',
            'dv_amount' => 'Amount Disbursed',
        ];
    }
}
