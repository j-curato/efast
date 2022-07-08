<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "jev_preparation".
 *
 * @property int $id
 * @property int $responsibility_center_id
 * @property int $fund_cluster_code_id
 * @property string $reporting_period
 * @property string $date
 * @property string $jev_number
 * @property string $dv_number
 * @property string $lddap_number
 * @property string $explaination
 *
 * @property JevAccountingEntries[] $jevAccountingEntries
 * @property FundClusterCode $fundClusterCode
 * @property ResponsibilityCenter $responsibilityCenter
 * @property Books $books
 */
class JevPreparation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'jev_preparation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['book_id', 'reporting_period', 'date', 'jev_number', 'explaination', 'check_ada_date', 'entry_type'], 'required'],
            [['responsibility_center_id', 'fund_cluster_code_id', 'cash_flow_id', 'payee_id', 'book_id', 'cash_disbursement_id'], 'integer'],
            [['date', 'check_ada_date', 'check_ada_number'], 'safe'],
            [['reporting_period', 'entry_type'], 'string', 'max' => 50],
            [['jev_number', 'dv_number', 'lddap_number', 'ref_number'], 'string', 'max' => 100],
            [['explaination',], 'string', 'max' => 1000],

            [[
                'responsibility_center_id',
                'fund_cluster_code_id',
                'reporting_period',
                'date',
                'jev_number',
                'lddap_number',
                'explaination',
                'ref_number',
                'cash_flow_id',
                'payee_id',
                'mrd_classification_id',
                'check_ada',
                'cadadr_serial_number',
                'check_ada_number',
                'book_id',
                'entry_type',
                'cash_disbursement_id',
                'check_ada_date',
                'dv_number',
            ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],

            [['jev_number',], 'unique'],
            [['entry_type',], 'string'],
            [['cadadr_serial_number', 'check_ada'], 'string', 'max' => 255],
            [['fund_cluster_code_id'], 'exist', 'skipOnError' => true, 'targetClass' => FundClusterCode::class, 'targetAttribute' => ['fund_cluster_code_id' => 'id']],
            [['responsibility_center_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResponsibilityCenter::class, 'targetAttribute' => ['responsibility_center_id' => 'id']],
            [['book_id'], 'exist', 'skipOnError' => true, 'targetClass' => Books::class, 'targetAttribute' => ['book_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'responsibility_center_id' => 'Responsibility Center ',
            'fund_cluster_code_id' => 'Fund Cluster Code ',
            'reporting_period' => 'Reporting Period',
            'date' => 'Date',
            'jev_number' => 'JEV Number',
            'dv_number' => 'Dv Number',
            'lddap_number' => 'Lddap Number',
            'explaination' => 'Particular',
            'ref_number' => 'Reference ',
            'cash_flow_id' => 'Cash Flow Transaction',
            'payee_id' => 'Payee',
            'mrd_classification_id' => 'MRD Classifcation',
            'check_ada' => 'Check ADA',
            'cadadr_serial_number' => 'CADADR Serial Number',
            'check_ada_number' => 'Check/ADA Number',

            'book_id' => 'Book',
            'entry_type' => 'Entry Type',
            'check_ada_date' => 'Check/ADA Date',
            'cash_disbursement_id' => 'DV Number',

        ];
    }

    /**
     * Gets query for [[JevAccountingEntries]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJevAccountingEntries()
    {
        return $this->hasMany(JevAccountingEntries::class, ['jev_preparation_id' => 'id']);
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
     * Gets query for [[ResponsibilityCenter]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResponsibilityCenter()
    {
        return $this->hasOne(ResponsibilityCenter::class, ['id' => 'responsibility_center_id']);
    }
    public function getBooks()
    {
        return $this->hasOne(Books::class, ['id' => 'book_id']);
    }
    public function getPayee()
    {
        return $this->hasOne(Payee::class, ['id' => 'payee_id']);
    }
    public function getCashDisbursement()
    {
        return $this->hasOne(CashDisbursement::class, ['id' => 'cash_disbursement_id']);
    }
}
