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
            [['book_id', 'dv_aucs_entries_id'], 'integer'],
            [['reporting_period', 'mode_of_payment', 'issuance_date'], 'string', 'max' => 50],
            [['check_or_ada_no', 'is_cancelled'], 'string', 'max' => 100],
            [['book_id'], 'exist', 'skipOnError' => true, 'targetClass' => Books::class, 'targetAttribute' => ['book_id' => 'id']],
            [['dv_aucs_entries_id'], 'exist', 'skipOnError' => true, 'targetClass' => DvAucsEntries::class, 'targetAttribute' => ['dv_aucs_entries_id' => 'id']],
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
            'dv_aucs_entries_id' => 'Dv Aucs ID',
            'reporting_period' => 'Reporting Period',
            'mode_of_payment' => 'Mood Of Payment',
            'check_or_ada_no' => 'Check Or Ada No',
            'is_cancelled' => 'Is Cancelled',
            'issuance_date' => 'Issuance Date',
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
    public function getDvAucsEntries()
    {
        return $this->hasOne(DvAucsEntries::class, ['id' => 'dv_aucs_entries_id']);
    }
}
