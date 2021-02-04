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
 * @property string $entity_name
 * @property string $explaination
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
        return 'jev_preparation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['responsibility_center_id', 'fund_cluster_code_id', 'reporting_period', 'date', 'jev_number', 'dv_number', 'lddap_number', 'entity_name', 'explaination'], 'required'],
            [['responsibility_center_id', 'fund_cluster_code_id'], 'integer'],
            [['date'], 'safe'],
            [['reporting_period'], 'string', 'max' => 50],
            [['jev_number', 'dv_number', 'lddap_number', 'entity_name', 'explaination'], 'string', 'max' => 100],
            [['fund_cluster_code_id'], 'exist', 'skipOnError' => true, 'targetClass' => FundClusterCode::className(), 'targetAttribute' => ['fund_cluster_code_id' => 'id']],
            [['responsibility_center_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResponsibilityCenter::className(), 'targetAttribute' => ['responsibility_center_id' => 'id']],
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
            'dv_number' => 'Dv Number',
            'lddap_number' => 'Lddap Number',
            'entity_name' => 'Entity Name',
            'explaination' => 'Explaination',
        ];
    }

    /**
     * Gets query for [[JevAccountingEntries]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJevAccountingEntries()
    {
        return $this->hasMany(JevAccountingEntries::className(), ['jev_preparation_id' => 'id']);
    }

    /**
     * Gets query for [[FundClusterCode]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFundClusterCode()
    {
        return $this->hasOne(FundClusterCode::className(), ['id' => 'fund_cluster_code_id']);
    }

    /**
     * Gets query for [[ResponsibilityCenter]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResponsibilityCenter()
    {
        return $this->hasOne(ResponsibilityCenter::className(), ['id' => 'responsibility_center_id']);
    }
}
