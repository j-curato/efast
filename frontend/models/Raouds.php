<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "raouds".
 *
 * @property int $id
 * @property int|null $record_allotment_id
 * @property int|null $process_ors_id
 * @property string|null $serial_number
 * @property string|null $reporting_period
 *
 * @property ProcessOrs $processOrs
 * @property RecordAllotments $recordAllotment
 */
class Raouds extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'raouds';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['record_allotment_id', 'process_ors_id'], 'integer'],
            [['serial_number'], 'string', 'max' => 50],
            [['reporting_period'], 'string', 'max' => 30],
            [['process_ors_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProcessOrs::className(), 'targetAttribute' => ['process_ors_id' => 'id']],
            [['record_allotment_id'], 'exist', 'skipOnError' => true, 'targetClass' => RecordAllotments::className(), 'targetAttribute' => ['record_allotment_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'record_allotment_id' => 'Record Allotment ID',
            'process_ors_id' => 'Process Ors ID',
            'serial_number' => 'Serial Number',
            'reporting_period' => 'Reporting Period',
        ];
    }

    /**
     * Gets query for [[ProcessOrs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProcessOrs()
    {
        return $this->hasOne(ProcessOrs::className(), ['id' => 'process_ors_id']);
    }

    /**
     * Gets query for [[RecordAllotment]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRecordAllotment()
    {
        return $this->hasOne(RecordAllotments::className(), ['id' => 'record_allotment_id']);
    }
}
