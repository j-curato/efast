<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "record_allotments".
 *
 * @property int $id
 * @property int|null $document_recieve_id
 * @property int|null $fund_cluster_code_id
 * @property int|null $financing_source_code_id
 * @property int|null $fund_category_and_classification_code_id
 * @property int|null $authorization_code_id
 * @property int|null $mfo_pap_code_id
 * @property int|null $fund_source_id
 * @property string $reporting_period
 * @property string $serial_number
 * @property string|null $allotment_number
 * @property string|null $date_issued
 * @property string|null $valid_until
 * @property string|null $particulars
 *
 * @property AuthorizationCode $authorizationCode
 * @property DocumentRecieve $documentRecieve
 * @property FinancingSourceCode $financingSourceCode
 * @property FundCategoryAndClassificationCode $fundCategoryAndClassificationCode
 * @property FundClusterCode $fundClusterCode
 * @property FundSource $fundSource
 * @property MfoPapCode $mfoPapCode
 */
class RecordAllotments extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'record_allotments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'document_recieve_id', 'fund_cluster_code_id',
                'financing_source_code_id', 'fund_category_and_classification_code_id', 'authorization_code_id',
                'mfo_pap_code_id', 'fund_source_id',
                'office_id',
                'allotment_type_id',
                'division_id',
                'book_id',
            ], 'integer'],
            [['reporting_period', 'serial_number'], 'required'],
            [['reporting_period'], 'string', 'max' => 20],
            [['serial_number', 'allotment_number', 'date_issued', 'valid_until'], 'string', 'max' => 50],
            [['particulars'], 'string', 'max' => 500],
            [[
                'id',
                'document_recieve_id',
                'fund_cluster_code_id',
                'financing_source_code_id',
                'fund_category_and_classification_code_id',
                'authorization_code_id',
                'mfo_pap_code_id',
                'fund_source_id',
                'reporting_period',
                'serial_number',
                'allotment_number',
                'date_issued',
                'valid_until',
                'particulars',
                'fund_classification',
                'book_id',
                'funding_code',
                'responsibility_center_id',

            ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],


            [['authorization_code_id'], 'exist', 'skipOnError' => true, 'targetClass' => AuthorizationCode::class, 'targetAttribute' => ['authorization_code_id' => 'id']],
            [['document_recieve_id'], 'exist', 'skipOnError' => true, 'targetClass' => DocumentRecieve::class, 'targetAttribute' => ['document_recieve_id' => 'id']],
            [['financing_source_code_id'], 'exist', 'skipOnError' => true, 'targetClass' => FinancingSourceCode::class, 'targetAttribute' => ['financing_source_code_id' => 'id']],
            [['fund_category_and_classification_code_id'], 'exist', 'skipOnError' => true, 'targetClass' => FundCategoryAndClassificationCode::class, 'targetAttribute' => ['fund_category_and_classification_code_id' => 'id']],
            [['fund_cluster_code_id'], 'exist', 'skipOnError' => true, 'targetClass' => FundClusterCode::class, 'targetAttribute' => ['fund_cluster_code_id' => 'id']],
            [['fund_source_id'], 'exist', 'skipOnError' => true, 'targetClass' => FundSource::class, 'targetAttribute' => ['fund_source_id' => 'id']],
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
            'document_recieve_id' => 'Document Recieve ',
            'fund_cluster_code_id' => 'Fund Cluster Code ',
            'financing_source_code_id' => 'Financing Source Code ',
            'fund_category_and_classification_code_id' => 'Fund Category And Classification Code ',
            'authorization_code_id' => 'Authorization Code ',
            'mfo_pap_code_id' => 'Mfo/Pap Code ',
            'fund_source_id' => 'Fund Source ',
            'reporting_period' => 'Reporting Period',
            'serial_number' => 'Serial Number',
            'allotment_number' => 'Allotment Number',
            'date_issued' => 'Date Issued',
            'valid_until' => 'Valid Until',
            'particulars' => 'Particulars',
            'office_id' => 'Office',
            'allotment_type_id' => 'Allotment Type',
            'division_id' => 'Division',
            'book_id' => 'Book',


        ];
    }

    /**
     * Gets query for [[AuthorizationCode]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthorizationCode()
    {
        return $this->hasOne(AuthorizationCode::class, ['id' => 'authorization_code_id']);
    }

    /**
     * Gets query for [[DocumentRecieve]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentRecieve()
    {
        return $this->hasOne(DocumentRecieve::class, ['id' => 'document_recieve_id']);
    }

    /**
     * Gets query for [[FinancingSourceCode]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFinancingSourceCode()
    {
        return $this->hasOne(FinancingSourceCode::class, ['id' => 'financing_source_code_id']);
    }

    /**
     * Gets query for [[FundCategoryAndClassificationCode]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFundCategoryAndClassificationCode()
    {
        return $this->hasOne(FundCategoryAndClassificationCode::class, ['id' => 'fund_category_and_classification_code_id']);
    }

    /**
     * Gets query for [[FundClusterCode]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFundClusterCode()
    {
        return $this->hasOne(FundClusterCode::class, ['id' => 'fund_cluster_code_id']);
    }

    /**
     * Gets query for [[FundSource]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFundSource()
    {
        return $this->hasOne(FundSource::class, ['id' => 'fund_source_id']);
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
    public function getBook()
    {
        return $this->hasOne(Books::class, ['id' => 'book_id']);
    }
    public function getResponsibilityCenter()
    {
        return $this->hasOne(ResponsibilityCenter::class, ['id' => 'responsibility_center_id']);
    }
    public function getRecordAllotmentEntries()
    {
        return $this->hasMany(RecordAllotmentEntries::class, ['record_allotment_id' => 'id']);
    }
}
