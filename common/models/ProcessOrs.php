<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%process_ors}}".
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
 * @property string|null $date
 * @property int|null $is_cancelled
 * @property string|null $type
 * @property string $created_at
 * @property string|null $transaction_begin_time
 *
 * @property Transaction $transaction
 * @property ProcessOrsEntries[] $processOrsEntries
 * @property Raouds[] $raouds
 * @property TrackingSheet[] $trackingSheets
 */
class ProcessOrs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%process_ors}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['transaction_id', 'document_recieve_id', 'mfo_pap_code_id', 'fund_source_id', 'book_id', 'is_cancelled'], 'integer'],
            [['created_at', 'transaction_begin_time'], 'safe'],
            [['transaction_id'], 'required'],
            [['reporting_period', 'serial_number', 'obligation_number'], 'string', 'max' => 255],
            [['funding_code'], 'string', 'max' => 50],
            [['date'], 'string', 'max' => 20],
            [['type'], 'string', 'max' => 10],
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
            [['transaction_id'], 'exist', 'skipOnError' => true, 'targetClass' => Transaction::class, 'targetAttribute' => ['transaction_id' => 'id']],
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
            'transaction_begin_time' => 'Transaction Begin Time',
        ];
    }

    /**
     * Gets query for [[Transaction]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\TransactionQuery
     */
    public function getTransaction()
    {
        return $this->hasOne(Transaction::class, ['id' => 'transaction_id']);
    }

    /**
     * Gets query for [[ProcessOrsEntries]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\ProcessOrsEntriesQuery
     */
    public function getProcessOrsEntries()
    {
        return $this->hasMany(ProcessOrsEntries::class, ['process_ors_id' => 'id']);
    }

    /**
     * Gets query for [[Raouds]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\RaoudsQuery
     */
    // public function getRaouds()
    // {
    //     return $this->hasMany(Raouds::class, ['process_ors_id' => 'id']);
    // }

    /**
     * Gets query for [[TrackingSheets]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\TrackingSheetQuery
     */
    public function getTrackingSheets()
    {
        return $this->hasMany(TrackingSheet::class, ['process_ors_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\ProcessOrsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\ProcessOrsQuery(get_called_class());
    }
}
