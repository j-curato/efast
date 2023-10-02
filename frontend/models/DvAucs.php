<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dv_aucs".
 *
 * @property int $id
 * @property int|null $process_ors_id
 * @property int|null $raoud_id
 * @property string|null $dv_number
 * @property string|null $reporting_period

 *
 * @property ProcessOrs $processOrs
 * @property Raouds $raoud
 */
class DvAucs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dv_aucs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {




        return [
            [['tracking_sheet_id'], 'number'],
            [[
                'fk_dv_transaction_type_id',
                'payroll_id',
                'fk_remittance_id',
                'payee_id',
                'is_payable',
                'mrd_classification_id',
                'book_id',
            ], 'integer'],
            [['dv_number', 'object_code', 'in_timestamp'], 'string', 'max' => 255],

            [['reporting_period'], 'string', 'max' => 50],
            [['particular', 'dv_link'], 'string'],
            [['recieved_at'], 'safe'],
            [[
                'particular',
                'payee_id',
                'reporting_period',
                'book_id',
                'recieved_at',
                'fk_dv_transaction_type_id',
            ], 'required'],
            [[

                'particular',
                'transaction_type',
            ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],



        ];
    }
    public static function getDetailedDv($year)
    {
        return Yii::$app->db->createCommand("CALL prc_GetDetailedDvs(:yr)")->bindValue(':yr', $year)->queryAll();
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'payee_id' => 'Payee',
            'dv_number' => 'Dv Number',
            'reporting_period' => 'Reporting Period',
            'transaction_begin_time' => 'Transaction Begin Time',
            'in_timestamp' => 'IN Timestamp',
            'tracking_sheet_id' => 'IN Timestamp',
            'is_payable' => 'Is Payable',
            'particular' => 'Particular',
            'mrd_classification_id' => 'MRD Classification',
            'object_code' => 'Object Code',
            'fk_dv_transaction_type_id' => 'Transaction Type',
            'payroll_id' => 'Payroll Number',
            'fk_remittance_id' => 'Remittance Number',
            'book_id' => 'Book',
            'recieved_at' => 'Receive at',
            'nature_of_transaction_id' => 'Nature of Transaction',
            'dv_link' => 'DV Link',

        ];
    }

    /**
     * Gets query for [[ProcessOrs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProcessOrs()
    {
        return $this->hasOne(ProcessOrs::class, ['id' => 'process_ors_id']);
    }

    /**
     * Gets query for [[Raoud]].
     *
     * @return \yii\db\ActiveQuery
     */

    public function getDvAucsEntries()
    {
        return $this->hasMany(DvAucsEntries::class, ['dv_aucs_id' => 'id']);
    }
    public function getNatureOfTransaction()
    {
        return $this->hasOne(NatureOfTransaction::class, ['id' => 'nature_of_transaction_id']);
    }
    public function getMrdClassification()
    {
        return $this->hasOne(MrdClassification::class, ['id' => 'mrd_classification_id']);
    }
    public function getPayee()
    {
        return $this->hasOne(Payee::class, ['id' => 'payee_id']);
    }
    public function getCashDisbursement()
    {
        return $this->hasOne(CashDisbursement::class, ['dv_aucs_id' => 'id']);
    }
    public function getDvAccountingEntries()
    {
        return $this->hasMany(DvAccountingEntries::class, ['dv_aucs_id' => 'id']);
    }
    public function getTrackingSheet()
    {
        return $this->hasOne(TrackingSheet::class, ['id' => 'tracking_sheet_id']);
    }
    public function getDvAucsFile()
    {
        return $this->hasOne(DvAucsFile::class, ['fk_dv_aucs_id' => 'id']);
    }
    public function getDvTransactionType()
    {
        return $this->hasOne(DvTransactionType::class, ['id' => 'fk_dv_transaction_type_id']);
    }
}
