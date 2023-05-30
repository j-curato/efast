<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "good_cash_disbursements".
 *
 * @property int $id
 * @property string|null $reporting_period
 * @property string|null $issuance_date
 * @property string|null $book_name
 * @property string|null $mode_name
 * @property string|null $check_or_ada_no
 * @property string|null $ada_number
 */
class VwGoodCashDisbursements extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vw_good_cash_disbursements';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['reporting_period', 'issuance_date'], 'string', 'max' => 50],
            [['book_name', 'mode_name'], 'string', 'max' => 255],
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
            'reporting_period' => 'Reporting Period',
            'issuance_date' => 'Issuance Date',
            'book_name' => 'Book Name',
            'mode_name' => 'Mode Name',
            'check_or_ada_no' => 'Check Or Ada No',
            'ada_number' => 'Ada Number',
        ];
    }
}
