<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "process_ors".
 *
 * @property int $id
 * @property int|null $transaction_id
 * @property string|null $reporting_period
 * @property string|null $serial_number
 * @property string|null $obligation_number
 * @property string|null $funding_code
 * @property int|null $document_recieve_id
 * @property int|null $mfo_pap_code_id
 * @property int|null $fund_source_id
 * @property int|null $book_id
 *
 * @property DvAucs[] $dvAucs
 * @property Transaction $transaction
 * @property ProcessOrsEntries[] $processOrsEntries
 * @property Raouds[] $raouds
 */
class ProcessOrs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'process_ors';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['transaction_id', 'document_recieve_id', 'mfo_pap_code_id', 'fund_source_id', 'book_id', 'is_cancelled'], 'integer'],
            [['reporting_period', 'serial_number', 'obligation_number', 'type'], 'string', 'max' => 255],
            [['funding_code'], 'string', 'max' => 50],
            [['is_cancelled'], 'default', 'value' => 0],
            [['type'], 'default', 'value' => 'ors'],
            [['transaction_id'], 'exist', 'skipOnError' => true, 'targetClass' => Transaction::class, 'targetAttribute' => ['transaction_id' => 'id']],
            [[
                'id',
                'transaction_id',
                'reporting_period',
                'serial_number',
                'obligation_number',
                'funding_code',
                'document_recieve_id',
                'mfo_pap_code_id',
                'fund_source_id',
                'book_id',
                'date',
                'is_cancelled',
                'type',
                'created_at',
                'transaction_begin_time',

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
            'transaction_id' => 'Transaction ID',
            'reporting_period' => 'Reporting Period',
            'serial_number' => 'Serial Number',
            'obligation_number' => 'Obligation Number',
            'funding_code' => 'Funding Code',
            'document_recieve_id' => 'Document Recieve ID',
            'mfo_pap_code_id' => 'Mfo Pap Code ID',
            'fund_source_id' => 'Fund Source ID',
            'book_id' => 'Book ID',
            'date' => 'Date',
            'is_cancelled' => 'Is Cancelled',
            'type' => 'Type',
            'created_at' => 'Created At',
            'transaction_begin_time' => 'Transaction Timestamp'
        ];
    }

    /**
     * Gets query for [[DvAucs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDvAucs()
    {
        return $this->hasMany(DvAucs::class, ['process_ors_id' => 'id']);
    }

    /**
     * Gets query for [[Transaction]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTransaction()
    {
        return $this->hasOne(Transaction::class, ['id' => 'transaction_id']);
    }

    /**
     * Gets query for [[ProcessOrsEntries]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProcessOrsEntries()
    {
        return $this->hasMany(ProcessOrsEntries::class, ['process_ors_id' => 'id']);
    }

    /**
     * Gets query for [[Raouds]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRaouds()
    {
        return $this->hasMany(Raouds::class, ['process_ors_id' => 'id']);
    }
    public function getDvAucsEntries()
    {
        return $this->hasMany(DvAucsEntries::class, ['process_ors_id' => 'id']);
    }
    public function getBook()
    {
        return $this->hasOne(Books::class, ['id' => 'book_id']);
    }
}
