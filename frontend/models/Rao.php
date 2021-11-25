<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rao".
 *
 * @property string|null $document_name
 * @property string|null $fund_cluster_code_name
 * @property string|null $financing_source_code_name
 * @property string|null $fund_category_and_classification_code_name
 * @property string|null $authorization_code_name
 * @property string|null $mfo_pap_code_name
 * @property string|null $fund_source_name
 * @property string|null $reporting_period
 * @property string|null $uacs
 * @property string|null $general_ledger
 * @property string|null $book_name
 * @property float|null $ors_amount
 * @property float $allotment_amount
 * @property string|null $division
 */
class Rao extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rao';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ors_amount', 'allotment_amount'], 'number'],
            [['document_name', 'fund_cluster_code_name', 'financing_source_code_name', 'fund_category_and_classification_code_name', 'authorization_code_name', 'mfo_pap_code_name', 'fund_source_name', 'general_ledger', 'book_name'], 'string', 'max' => 255],
            [['reporting_period'], 'string', 'max' => 20],
            [['uacs'], 'string', 'max' => 30],
            [['division'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'document_name' => 'Document Name',
            'fund_cluster_code_name' => 'Fund Cluster Code Name',
            'financing_source_code_name' => 'Financing Source Code Name',
            'fund_category_and_classification_code_name' => 'Fund Category And Classification Code Name',
            'authorization_code_name' => 'Authorization Code Name',
            'mfo_pap_code_name' => 'Mfo Pap Code Name',
            'fund_source_name' => 'Fund Source Name',
            'reporting_period' => 'Reporting Period',
            'uacs' => 'Uacs',
            'general_ledger' => 'General Ledger',
            'book_name' => 'Book Name',
            'ors_amount' => 'Ors Amount',
            'allotment_amount' => 'Allotment Amount',
            'division' => 'Division',
            'is_cancelled' => 'Cancelled',
        ];
    }
}
