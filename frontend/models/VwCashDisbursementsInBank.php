<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vw_cash_disbursements_in_bank".
 *
 * @property int $id
 * @property string|null $reporting_period
 * @property string|null $ada_number
 * @property string|null $check_or_ada_no
 * @property string|null $book_name
 * @property string|null $mode_name
 */
class VwCashDisbursementsInBank extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vw_cash_disbursements_in_bank';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['reporting_period'], 'string', 'max' => 50],
            [['ada_number'], 'string', 'max' => 40],
            [['check_or_ada_no'], 'string', 'max' => 100],
            [['book_name', 'mode_name'], 'string', 'max' => 255],
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
            'ada_number' => 'Ada Number',
            'check_or_ada_no' => 'Check Or Ada No',
            'book_name' => 'Book Name',
            'mode_name' => 'Mode Name',
        ];
    }
}
