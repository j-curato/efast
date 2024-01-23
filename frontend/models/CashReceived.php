<?php

namespace app\models;

use Yii;
use app\behaviors\HistoryLogsBehavior;

/**
 * This is the model class for table "cash_received".
 *
 * @property int $id
 * @property int|null $document_recieved_id
 * @property int|null $book_id
 * @property int|null $mfo_pap_code_id
 * @property string|null $date
 * @property string|null $reporting_period
 * @property string|null $nca_no
 * @property string|null $nta_no
 * @property string|null $nft_no
 * @property string|null $purpose
 * @property float|null $amount
 *
 * @property Books $book
 * @property DocumentRecieve $documentRecieved
 * @property MfoPapCode $mfoPapCode
 */
class CashReceived extends \yii\db\ActiveRecord
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
        return 'cash_received';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['document_recieved_id', 'book_id', 'mfo_pap_code_id'], 'integer'],
            [[
                'document_recieved_id', 'book_id', 'date', 'reporting_period', 'nca_no', 'purpose', 'amount',
                'valid_from',
                'valid_to',
            ], 'required'],
            [['amount'], 'number'],
            [[
                'date',
                'valid_from',
                'valid_to',
            ], 'string', 'max' => 50],
            [['reporting_period'], 'string', 'max' => 40],
            [['nca_no', 'nta_no', 'nft_no', 'account_number'], 'string', 'max' => 100],
            [['purpose'], 'string', 'max' => 255],
            [[

                'nca_no',
                'nta_no',
                'nft_no',
                'purpose',
                'account_number',
            ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],



            [['book_id'], 'exist', 'skipOnError' => true, 'targetClass' => Books::class, 'targetAttribute' => ['book_id' => 'id']],
            [['document_recieved_id'], 'exist', 'skipOnError' => true, 'targetClass' => DocumentRecieve::class, 'targetAttribute' => ['document_recieved_id' => 'id']],
            [['mfo_pap_code_id'], 'exist', 'skipOnError' => true, 'targetClass' => MfoPapCode::class, 'targetAttribute' => ['mfo_pap_code_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'document_recieved_id' => 'Document Received ',
            'book_id' => 'Book ',
            'mfo_pap_code_id' => 'Mfo Pap Code ',
            'date' => 'Date',
            'reporting_period' => 'Reporting Period',
            'nca_no' => 'Nca No',
            'nta_no' => 'Nta No',
            'nft_no' => 'Nft No',
            'purpose' => 'Purpose',
            'amount' => 'Amount',
            'account_number' => 'Account Number',
            'valid_from' => 'Valid From',
            'valid_to' => 'Valid To',
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
     * Gets query for [[DocumentRecieved]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentRecieved()
    {
        return $this->hasOne(DocumentRecieve::class, ['id' => 'document_recieved_id']);
    }

    /**
     * Gets query for [[MfoPapCode]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMfoPapCode()
    {
        return $this->hasOne(MfoPapCode::class, ['id' => 'mfo_pap_code_id']);
    }
}
