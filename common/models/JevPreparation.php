<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%jev_preparation}}".
 *
 * @property int $id
 * @property int|null $responsibility_center_id
 * @property int|null $fund_cluster_code_id
 * @property string $reporting_period
 * @property string|null $date
 * @property string|null $jev_number
 * @property string|null $ref_number
 * @property string|null $dv_number
 * @property string|null $lddap_number
 * @property string $explaination
 * @property int|null $payee_id
 * @property int|null $cash_flow_id
 * @property int|null $mrd_classification_id
 * @property string|null $cadadr_serial_number
 * @property string|null $check_ada
 * @property string|null $check_ada_number
 * @property string $check_ada_date
 * @property int|null $book_id
 * @property string $created_at
 * @property int|null $cash_disbursement_id
 *
 * @property JevAccountingEntries[] $jevAccountingEntries
 * @property FundClusterCode $fundClusterCode
 * @property ResponsibilityCenter $responsibilityCenter
 */
class JevPreparation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%jev_preparation}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['responsibility_center_id', 'fund_cluster_code_id', 'payee_id', 'cash_flow_id', 'mrd_classification_id', 'book_id', 'cash_disbursement_id'], 'integer'],
            [['reporting_period', 'explaination'], 'required'],
            [['created_at'], 'safe'],
            [['reporting_period', 'date'], 'string', 'max' => 50],
            [['jev_number', 'ref_number', 'dv_number', 'lddap_number'], 'string', 'max' => 100],
            [['explaination'], 'string', 'max' => 1000],
            [['cadadr_serial_number', 'check_ada', 'check_ada_number', 'check_ada_date'], 'string', 'max' => 255],
            [[
                'id',
                'responsibility_center_id',
                'fund_cluster_code_id',
                'reporting_period',
                'date',
                'jev_number',
                'ref_number',
                'dv_number',
                'lddap_number',
                'explaination',
                'payee_id',
                'cash_flow_id',
                'mrd_classification_id',
                'cadadr_serial_number',
                'check_ada',
                'check_ada_number',
                'check_ada_date',
                'book_id',
                'created_at',
                'cash_disbursement_id',
            ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
            [['fund_cluster_code_id'], 'exist', 'skipOnError' => true, 'targetClass' => FundClusterCode::class, 'targetAttribute' => ['fund_cluster_code_id' => 'id']],
            [['responsibility_center_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResponsibilityCenter::class, 'targetAttribute' => ['responsibility_center_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'responsibility_center_id' => 'Responsibility Center ID',
            'fund_cluster_code_id' => 'Fund Cluster Code ID',
            'reporting_period' => 'Reporting Period',
            'date' => 'Date',
            'jev_number' => 'Jev Number',
            'ref_number' => 'Ref Number',
            'dv_number' => 'Dv Number',
            'lddap_number' => 'Lddap Number',
            'explaination' => 'Explaination',
            'payee_id' => 'Payee ID',
            'cash_flow_id' => 'Cash Flow ID',
            'mrd_classification_id' => 'Mrd Classification ID',
            'cadadr_serial_number' => 'Cadadr Serial Number',
            'check_ada' => 'Check Ada',
            'check_ada_number' => 'Check Ada Number',
            'check_ada_date' => 'Check Ada Date',
            'book_id' => 'Book ID',
            'created_at' => 'Created At',
            'cash_disbursement_id' => 'Cash Disbursement ID',
        ];
    }

    /**
     * Gets query for [[JevAccountingEntries]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\JevAccountingEntriesQuery
     */
    public function getJevAccountingEntries()
    {
        return $this->hasMany(JevAccountingEntries::class, ['jev_preparation_id' => 'id']);
    }

    /**
     * Gets query for [[FundClusterCode]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\FundClusterCodeQuery
     */
    public function getFundClusterCode()
    {
        return $this->hasOne(FundClusterCode::class, ['id' => 'fund_cluster_code_id']);
    }

    /**
     * Gets query for [[ResponsibilityCenter]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\ResponsibilityCenterQuery
     */
    public function getResponsibilityCenter()
    {
        return $this->hasOne(ResponsibilityCenter::class, ['id' => 'responsibility_center_id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\JevPreparationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\JevPreparationQuery(get_called_class());
    }
}
