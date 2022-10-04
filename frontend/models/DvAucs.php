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
            [['tracking_sheet_id', 'is_payable'], 'number'],
            [['dv_number', 'object_code'], 'string', 'max' => 255],

            [['reporting_period'], 'string', 'max' => 50],
            [['particular', 'payee_id'], 'required'],
            [[

                'id',
                'dv_number',
                'reporting_period',
                'mrd_classification_id',
                'nature_of_transaction_id',
                'particular',
                'payee_id',
                'transaction_type',
                'book_id',
                'is_cancelled',
                'created_at',
                'dv_link',
                'transaction_begin_time',
                'return_timestamp',
                'out_timestamp',
                'accept_timestamp',
                'tracking_sheet_id',
                'in_timestamp',
                'is_payable',
                'recieved_at',
                'payroll_id',
            ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],


        ];
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
    public function getRaoud()
    {
        return $this->hasOne(Raouds::class, ['id' => 'raoud_id']);
    }
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
}
