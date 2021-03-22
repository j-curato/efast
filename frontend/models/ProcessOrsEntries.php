<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "process_ors_entries".
 *
 * @property int $id
 * @property int $chart_of_account_id
 * @property int $process_ors_id
 * @property float $amount
 *
 * @property ChartOfAccounts $chartOfAccount
 * @property ProcessOrs $processOrs
 */
class ProcessOrsEntries extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'process_ors_entries';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['chart_of_account_id', 'process_ors_id', 'amount'], 'required'],
            [['chart_of_account_id', 'process_ors_id'], 'integer'],
            [['amount'], 'number'],
            [['chart_of_account_id'], 'exist', 'skipOnError' => true, 'targetClass' => ChartOfAccounts::className(), 'targetAttribute' => ['chart_of_account_id' => 'id']],
            [['process_ors_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProcessOrs::className(), 'targetAttribute' => ['process_ors_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'chart_of_account_id' => 'Chart Of Account ID',
            'process_ors_id' => 'Process Ors ID',
            'amount' => 'Amount',
        ];
    }

    /**
     * Gets query for [[ChartOfAccount]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getChartOfAccount()
    {
        return $this->hasOne(ChartOfAccounts::class, ['id' => 'chart_of_account_id']);
    }

    /**
     * Gets query for [[ProcessOrs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProcessOrs()
    {
        return $this->hasOne(ProcessOrs::class, ['id' => 'process_ors_id']);
    }
}
