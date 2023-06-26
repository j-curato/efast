<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vw_gd_no_rci_checks".
 *
 * @property int $id
 * @property string|null $check_or_ada_no
 * @property string|null $ada_number
 * @property string|null $issuance_date
 * @property string|null $book_name
 * @property string|null $reporting_period
 * @property string|null $mode_name
 * @property float|null $ttlDisbursed
 * @property float|null $ttlTax
 */
class VwGdNoRciChecks extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vw_gd_no_rci_checks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id'], 'integer'],
            [['ttlDisbursed', 'ttlTax'], 'number'],
            [['check_or_ada_no'], 'string', 'max' => 100],
            [['ada_number'], 'string', 'max' => 40],
            [['issuance_date', 'reporting_period'], 'string', 'max' => 50],
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
            'check_or_ada_no' => 'Check No',
            'ada_number' => 'ADA No',
            'issuance_date' => 'Check Date',
            'book_name' => 'Book ',
            'reporting_period' => 'Reporting Period',
            'mode_name' => 'Mode of Pyment',
            'ttlDisbursed' => 'Amount Disbursed',
            'ttlTax' => ' Tax Withheld',
        ];
    }
}
