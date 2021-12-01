<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%record_allotments}}".
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
 * @property int|null $fund_classification
 * @property int|null $book_id
 * @property string|null $funding_code
 * @property int|null $responsibility_center_id
 *
 * @property RecordAllotmentEntries[] $recordAllotmentEntries
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
        return '{{%record_allotments}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['document_recieve_id', 'fund_cluster_code_id', 'financing_source_code_id', 'fund_category_and_classification_code_id', 'authorization_code_id', 'mfo_pap_code_id', 'fund_source_id', 'fund_classification', 'book_id', 'responsibility_center_id'], 'integer'],
            [['reporting_period', 'serial_number'], 'required'],
            [['particulars'], 'string'],
            [['reporting_period'], 'string', 'max' => 20],
            [['serial_number', 'allotment_number', 'date_issued', 'valid_until', 'funding_code'], 'string', 'max' => 50],
            [['authorization_code_id'], 'exist', 'skipOnError' => true, 'targetClass' => AuthorizationCode::className(), 'targetAttribute' => ['authorization_code_id' => 'id']],
            [['document_recieve_id'], 'exist', 'skipOnError' => true, 'targetClass' => DocumentRecieve::className(), 'targetAttribute' => ['document_recieve_id' => 'id']],
            [['financing_source_code_id'], 'exist', 'skipOnError' => true, 'targetClass' => FinancingSourceCode::className(), 'targetAttribute' => ['financing_source_code_id' => 'id']],
            [['fund_category_and_classification_code_id'], 'exist', 'skipOnError' => true, 'targetClass' => FundCategoryAndClassificationCode::className(), 'targetAttribute' => ['fund_category_and_classification_code_id' => 'id']],
            [['fund_cluster_code_id'], 'exist', 'skipOnError' => true, 'targetClass' => FundClusterCode::className(), 'targetAttribute' => ['fund_cluster_code_id' => 'id']],
            [['fund_source_id'], 'exist', 'skipOnError' => true, 'targetClass' => FundSource::className(), 'targetAttribute' => ['fund_source_id' => 'id']],
            [['mfo_pap_code_id'], 'exist', 'skipOnError' => true, 'targetClass' => MfoPapCode::className(), 'targetAttribute' => ['mfo_pap_code_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'document_recieve_id' => 'Document Recieve ID',
            'fund_cluster_code_id' => 'Fund Cluster Code ID',
            'financing_source_code_id' => 'Financing Source Code ID',
            'fund_category_and_classification_code_id' => 'Fund Category And Classification Code ID',
            'authorization_code_id' => 'Authorization Code ID',
            'mfo_pap_code_id' => 'Mfo Pap Code ID',
            'fund_source_id' => 'Fund Source ID',
            'reporting_period' => 'Reporting Period',
            'serial_number' => 'Serial Number',
            'allotment_number' => 'Allotment Number',
            'date_issued' => 'Date Issued',
            'valid_until' => 'Valid Until',
            'particulars' => 'Particulars',
            'fund_classification' => 'Fund Classification',
            'book_id' => 'Book ID',
            'funding_code' => 'Funding Code',
            'responsibility_center_id' => 'Responsibility Center ID',
        ];
    }

    /**
     * Gets query for [[RecordAllotmentEntries]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\RecordAllotmentEntriesQuery
     */
    public function getRecordAllotmentEntries()
    {
        return $this->hasMany(RecordAllotmentEntries::className(), ['record_allotment_id' => 'id']);
    }

    /**
     * Gets query for [[AuthorizationCode]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\AuthorizationCodeQuery
     */
    public function getAuthorizationCode()
    {
        return $this->hasOne(AuthorizationCode::className(), ['id' => 'authorization_code_id']);
    }

    /**
     * Gets query for [[DocumentRecieve]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\DocumentRecieveQuery
     */
    public function getDocumentRecieve()
    {
        return $this->hasOne(DocumentRecieve::className(), ['id' => 'document_recieve_id']);
    }

    /**
     * Gets query for [[FinancingSourceCode]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\FinancingSourceCodeQuery
     */
    public function getFinancingSourceCode()
    {
        return $this->hasOne(FinancingSourceCode::className(), ['id' => 'financing_source_code_id']);
    }

    /**
     * Gets query for [[FundCategoryAndClassificationCode]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\FundCategoryAndClassificationCodeQuery
     */
    public function getFundCategoryAndClassificationCode()
    {
        return $this->hasOne(FundCategoryAndClassificationCode::className(), ['id' => 'fund_category_and_classification_code_id']);
    }

    /**
     * Gets query for [[FundClusterCode]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\FundClusterCodeQuery
     */
    public function getFundClusterCode()
    {
        return $this->hasOne(FundClusterCode::className(), ['id' => 'fund_cluster_code_id']);
    }

    /**
     * Gets query for [[FundSource]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\FundSourceQuery
     */
    public function getFundSource()
    {
        return $this->hasOne(FundSource::className(), ['id' => 'fund_source_id']);
    }

    /**
     * Gets query for [[MfoPapCode]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\MfoPapCodeQuery
     */
    public function getMfoPapCode()
    {
        return $this->hasOne(MfoPapCode::className(), ['id' => 'mfo_pap_code_id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\RecordAllotmentsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\RecordAllotmentsQuery(get_called_class());
    }
}
