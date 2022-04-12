<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "payroll".
 *
 * @property int $id
 * @property string $payroll_number
 * @property string $reporting_period
 * @property int $process_ors_id
 * @property string $type
 * @property float|null $amount
 * @property string $created_at
 */
class Payroll extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payroll';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'process_ors_id'], 'integer'],
            [['amount', 'due_to_bir_amount'], 'number'],
            [['created_at'], 'safe'],
            [['payroll_number', 'reporting_period', 'type'], 'string', 'max' => 255],
            [['payroll_number'], 'unique'],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'payroll_number' => 'Payroll Number',
            'reporting_period' => 'Reporting Period',
            'process_ors_id' => 'Process Ors ID',
            'type' => 'Type',
            'amount' => 'Amount',
            'due_to_bir_amount' => 'Due to BIR Amount',
            'created_at' => 'Created At',
        ];
    }
    public function getProcessOrs()
    {
        return $this->hasOne(ProcessOrs::class,['id'=>'process_ors_id']);
    }
}
