<?php

namespace app\models;

use Yii;
use app\behaviors\HistoryLogsBehavior;
use yii\db\Expression;

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
    public function behaviors()
    {
        return [
            HistoryLogsBehavior::class
        ];
    }
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
            [['transaction_id',  'book_id', 'date', 'reporting_period'], 'required'],
            [['reporting_period', 'serial_number', 'obligation_number', 'type'], 'string', 'max' => 255],
            [['funding_code'], 'string', 'max' => 50],
            [['is_cancelled'], 'default', 'value' => 0],
            [['type'], 'default', 'value' => 'ors'],
            [['transaction_id'], 'exist', 'skipOnError' => true, 'targetClass' => Transaction::class, 'targetAttribute' => ['transaction_id' => 'id']],

        ];
    }

    public static function getDetailedProcessOrs($year, $type)
    {
        return Yii::$app->db->createCommand("CALL prc_GetDetailedProcessOrs(:yr,:typ)")
            ->bindValue(':yr', $year)
            ->bindValue(':typ', $type)
            ->queryAll();
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'transaction_id' => 'Transaction ',
            'reporting_period' => 'Reporting Period',
            'serial_number' => 'Serial Number',
            'obligation_number' => 'Obligation Number',
            'funding_code' => 'Funding Code',
            'document_recieve_id' => 'Document Recieve ID',
            'mfo_pap_code_id' => 'Mfo Pap Code ID',
            'fund_source_id' => 'Fund Source ID',
            'book_id' => 'Book ',
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
    public function getDvs()
    {
        return DvAucs::find()
            ->addSelect([
                "dv_aucs.id",
                "dv_aucs.dv_number",
                "dv_aucs.particular",
                new Expression("payee.registered_name as payee_name"),
                new Expression("COALESCE(dv_aucs_entries.amount_disbursed,0) +
                COALESCE(dv_aucs_entries.compensation,0) +
                COALESCE(dv_aucs_entries.vat_nonvat,0) +
                COALESCE(dv_aucs_entries.ewt_goods_services,0) +
                COALESCE(dv_aucs_entries.liquidation_damage,0) +
                COALESCE(dv_aucs_entries.other_trust_liabilities,0) +
                COALESCE(dv_aucs_entries.tax_portion_of_post,0) as gross_amount")
            ])
            ->join("JOIN", "dv_aucs_entries", "dv_aucs.id = dv_aucs_entries.dv_aucs_id")
            ->join("LEFT JOIN", "payee", "dv_aucs.payee_id = payee.id")
            ->andWhere([
                "dv_aucs_entries.is_deleted" => false
            ])
            ->andWhere([
                'dv_aucs_entries.process_ors_id' => $this->id
            ])
            ->asArray()
            ->all();
    }
}
