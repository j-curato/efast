<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%process_ors_entries}}".
 *
 * @property int $id
 * @property int $chart_of_account_id
 * @property int $process_ors_id
 * @property float|null $amount
 * @property string|null $reporting_period
 * @property int|null $record_allotment_entries_id
 * @property int|null $is_realign
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
        return '{{%process_ors_entries}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['chart_of_account_id', 'process_ors_id'], 'required'],
            [['chart_of_account_id', 'process_ors_id', 'record_allotment_entries_id', 'is_realign'], 'integer'],
            [['amount'], 'number'],
            [['reporting_period'], 'string', 'max' => 20],
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
            'reporting_period' => 'Reporting Period',
            'record_allotment_entries_id' => 'Record Allotment Entries ID',
            'is_realign' => 'Is Realign',
        ];
    }

    /**
     * Gets query for [[ChartOfAccount]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\ChartOfAccountsQuery
     */
    public function getChartOfAccount()
    {
        return $this->hasOne(ChartOfAccounts::className(), ['id' => 'chart_of_account_id']);
    }

    /**
     * Gets query for [[ProcessOrs]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\ProcessOrsQuery
     */
    public function getProcessOrs()
    {
        return $this->hasOne(ProcessOrs::className(), ['id' => 'process_ors_id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\ProcessOrsEntriesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\ProcessOrsEntriesQuery(get_called_class());
    }
}
