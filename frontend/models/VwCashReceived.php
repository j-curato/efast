<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vw_cash_received".
 *
 * @property int $id
 * @property string|null $date
 * @property string|null $reporting_period
 * @property string|null $valid_from
 * @property string|null $valid_to
 * @property string|null $purpose
 * @property float|null $amount
 * @property string|null $nca_no
 * @property string|null $nta_no
 * @property string|null $document_receive_name
 * @property string|null $book_name
 * @property string|null $mfo_name
 */
class VwCashReceived extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vw_cash_received';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['valid_from', 'valid_to'], 'safe'],
            [['amount'], 'number'],
            [['date'], 'string', 'max' => 50],
            [['reporting_period'], 'string', 'max' => 40],
            [['purpose', 'document_receive_name', 'book_name'], 'string', 'max' => 255],
            [['nca_no', 'nta_no'], 'string', 'max' => 100],
            [['mfo_name'], 'string', 'max' => 511],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Date',
            'reporting_period' => 'Reporting Period',
            'valid_from' => 'Valid From',
            'valid_to' => 'Valid To',
            'purpose' => 'Purpose',
            'amount' => 'Amount',
            'nca_no' => 'Nca No',
            'nta_no' => 'Nta No',
            'document_receive_name' => 'Document Receive ',
            'book_name' => 'Book ',
            'mfo_name' => 'MFO/PAP',
        ];
    }
}
