<?php

namespace app\models;


use Yii;

/**
 * This is the model class for table "cash_disbursement".
 *
 * @property int $id
 * @property int|null $book_id
 * @property int|null $dv_aucs_entries_id
 * @property string|null $reporting_period
 * @property string|null $mode_of_payment
 * @property string|null $check_or_ada_no
 * @property string|null $is_cancelled
 * @property string|null $issuance_date
 *
 * @property Books $book
 * @property DvAucs $dvAucs
 */
class CashDisbursement extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cash_disbursement';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [


            [['book_id', 'dv_aucs_id', 'reporting_period', 'mode_of_payment', 'issuance_date'], 'required'],
            [['book_id', 'dv_aucs_id', 'is_cancelled'], 'integer'],
            [['reporting_period', 'mode_of_payment', 'issuance_date'], 'string', 'max' => 50],
            [['ada_number'], 'string', 'max' => 40],
            [['check_or_ada_no'], 'string', 'max' => 100],
            [['book_id'], 'exist', 'skipOnError' => true, 'targetClass' => Books::class, 'targetAttribute' => ['book_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'book_id' => 'Book ID',
            'dv_aucs_id' => 'Dv Aucs ID',
            'reporting_period' => 'Reporting Period',
            'mode_of_payment' => 'Mode Of Payment',
            'check_or_ada_no' => 'Check Or Ada No',
            'is_cancelled' => 'Is Cancelled',
            'issuance_date' => 'Issuance Date',
            'ada_number' => 'Ada Number',
        ];
    }

    /**
     * Gets query for [[Book]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBook()
    {
        return $this->hasOne(Books::class, ['id' => 'book_id']);
    }

    /**
     * Gets query for [[DvAucs]].
     *
     * @return \yii\db\ActiveQuery
     */

    public function getDvAucs()
    {
        return $this->hasOne(DvAucs::class, ['id' => 'dv_aucs_id']);
    }
    public function getJevPreparation()
    {
        return $this->hasOne(JevPreparation::class, ['cash_disbursement_id' => 'id']);
    }
}
