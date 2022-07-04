<?php

namespace common\models;

use app\models\MrdClassification;
use Yii;

/**
 * This is the model class for table "{{%dv_aucs}}".
 *
 * @property int $id
 * @property string|null $dv_number
 * @property string|null $reporting_period
 * @property string|null $tax_withheld
 * @property string|null $other_trust_liability_withheld
 * @property float|null $net_amount_paid
 * @property int|null $mrd_classification_id
 * @property int|null $nature_of_transaction_id
 * @property string|null $particular
 * @property int|null $payee_id
 * @property string|null $transaction_type
 * @property int|null $book_id
 * @property int|null $is_cancelled
 * @property string $created_at
 * @property string|null $dv_link
 * @property string|null $transaction_begin_time
 * @property string|null $return_timestamp
 * @property string|null $out_timestamp
 * @property string|null $accept_timestamp
 * @property int|null $tracking_sheet_id
 * @property string|null $in_timestamp
 *
 * @property CashDisbursement[] $cashDisbursements
 * @property DvAccountingEntries[] $dvAccountingEntries
 * @property MrdClassification $mrdClassification
 * @property DvAucsEntries[] $dvAucsEntries
 */
class DvAucs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%dv_aucs}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['net_amount_paid'], 'number'],
            [['mrd_classification_id', 'nature_of_transaction_id', 'payee_id', 'book_id', 'is_cancelled', 'tracking_sheet_id'], 'integer'],
            [['particular', 'dv_link'], 'string'],
            [['created_at', 'transaction_begin_time', 'return_timestamp', 'out_timestamp', 'accept_timestamp', 'in_timestamp', 'recieved_at'], 'safe'],
            [['dv_number', 'tax_withheld', 'other_trust_liability_withheld'], 'string', 'max' => 255],
            [['reporting_period', 'transaction_type'], 'string', 'max' => 50],
            [[
                'id',
                'dv_number',
                'reporting_period',
                'tax_withheld',
                'other_trust_liability_withheld',
                'net_amount_paid',
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
                'recieved_at',
            ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
            [['mrd_classification_id'], 'exist', 'skipOnError' => true, 'targetClass' => MrdClassification::class, 'targetAttribute' => ['mrd_classification_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dv_number' => 'Dv Number',
            'reporting_period' => 'Reporting Period',
            'tax_withheld' => 'Tax Withheld',
            'other_trust_liability_withheld' => 'Other Trust Liability Withheld',
            'net_amount_paid' => 'Net Amount Paid',
            'mrd_classification_id' => 'Mrd Classification ID',
            'nature_of_transaction_id' => 'Nature Of Transaction ID',
            'particular' => 'Particular',
            'payee_id' => 'Payee ID',
            'transaction_type' => 'Transaction Type',
            'book_id' => 'Book ID',
            'is_cancelled' => 'Is Cancelled',
            'created_at' => 'Created At',
            'dv_link' => 'Dv Link',
            'transaction_begin_time' => 'Transaction Begin Time',
            'return_timestamp' => 'Return Timestamp',
            'out_timestamp' => 'Out Timestamp',
            'accept_timestamp' => 'Accept Timestamp',
            'tracking_sheet_id' => 'Tracking Sheet ID',
            'in_timestamp' => 'In Timestamp',
            'recieved_at' => 'Recieve  Timestamp',

        ];
    }

    /**
     * Gets query for [[CashDisbursements]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\CashDisbursementQuery
     */
    public function getCashDisbursements()
    {
        return $this->hasMany(CashDisbursement::class, ['dv_aucs_id' => 'id']);
    }

    /**
     * Gets query for [[DvAccountingEntries]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\DvAccountingEntriesQuery
     */
    public function getDvAccountingEntries()
    {
        return $this->hasMany(DvAccountingEntries::class, ['dv_aucs_id' => 'id']);
    }

    /**
     * Gets query for [[MrdClassification]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\MrdClassificationQuery
     */
    public function getMrdClassification()
    {
        return $this->hasOne(MrdClassification::class, ['id' => 'mrd_classification_id']);
    }

    /**
     * Gets query for [[DvAucsEntries]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\DvAucsEntriesQuery
     */
    public function getDvAucsEntries()
    {
        return $this->hasMany(DvAucsEntries::class, ['dv_aucs_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\DvAucsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\DvAucsQuery(get_called_class());
    }
}
