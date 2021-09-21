<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "record_allotments_view".
 *
 * @property int $id
 * @property int|null $entry_id
 * @property string $reporting_period
 * @property string $serial_number
 * @property string|null $date_issued
 * @property string|null $valid_until
 * @property string|null $particulars
 * @property string|null $document_recieve
 * @property string|null $fund_cluster_code
 * @property string|null $financing_source_code
 * @property string|null $fund_classification
 * @property string|null $authorization_code
 * @property string|null $mfo_code
 * @property string|null $mfo_name
 * @property string|null $responsibility_center
 * @property string|null $fund_source
 * @property string|null $uacs
 * @property string|null $general_ledger
 * @property string|null $allotment_class
 * @property float|null $amount
 * @property string|null $nca_nta
 * @property string|null $carp_101
 */
class RecordAllotmentsView extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'record_allotments_view';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'entry_id'], 'integer'],
            [['reporting_period', 'serial_number'], 'required'],
            [['particulars'], 'string'],
            [['amount'], 'number'],
            [['reporting_period'], 'string', 'max' => 20],
            [['serial_number', 'date_issued', 'valid_until'], 'string', 'max' => 50],
            [['document_recieve', 'fund_cluster_code', 'financing_source_code', 'fund_classification', 'authorization_code', 'mfo_code', 'mfo_name', 'responsibility_center', 'fund_source', 'general_ledger', 'allotment_class'], 'string', 'max' => 255],
            [['uacs'], 'string', 'max' => 30],
            [['nca_nta'], 'string', 'max' => 3],
            [['carp_101'], 'string', 'max' => 4],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'entry_id' => 'Entry ID',
            'reporting_period' => 'Reporting Period',
            'serial_number' => 'Serial Number',
            'date_issued' => 'Date Issued',
            'valid_until' => 'Valid Until',
            'particulars' => 'Particulars',
            'document_recieve' => 'Document Recieve',
            'fund_cluster_code' => 'Fund Cluster Code',
            'financing_source_code' => 'Financing Source Code',
            'fund_classification' => 'Fund Classification',
            'authorization_code' => 'Authorization Code',
            'mfo_code' => 'Mfo Code',
            'mfo_name' => 'Mfo Name',
            'responsibility_center' => 'Responsibility Center',
            'fund_source' => 'Fund Source',
            'uacs' => 'Uacs',
            'general_ledger' => 'General Ledger',
            'allotment_class' => 'Allotment Class',
            'amount' => 'Amount',
            'nca_nta' => 'Nca Nta',
            'carp_101' => 'Carp 101',
        ];
    }
}
