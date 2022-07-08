<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "process_burs".
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
 *
 * @property Transaction $transaction
 */
class ProcessBurs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'process_burs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['transaction_id', 'document_recieve_id', 'mfo_pap_code_id', 'fund_source_id'], 'integer'],
            [['reporting_period', 'serial_number', 'obligation_number'], 'string', 'max' => 255],
            [['funding_code'], 'string', 'max' => 50],
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
        ];
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
    public function getRaouds()
    {
        return $this->hasMany(Raouds::class, ['process_burs_id' => 'id']);
    }
}
