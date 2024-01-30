<?php

namespace app\models;

use Yii;
use app\behaviors\HistoryLogsBehavior;
use DateTime;
use yii\db\Expression;

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
    public function behaviors()
    {
        return [
            HistoryLogsBehavior::class
        ];
    }
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
            [['chart_of_account_id', 'process_ors_id', 'amount', 'reporting_period'], 'required'],
            [['chart_of_account_id', 'process_ors_id'], 'integer'],
            [['amount'], 'number'],
            [['serial_number'], 'string'],
            [['serial_number'], 'unique'],

            [['chart_of_account_id'], 'exist', 'skipOnError' => true, 'targetClass' => ChartOfAccounts::class, 'targetAttribute' => ['chart_of_account_id' => 'id']],
            [['process_ors_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProcessOrs::class, 'targetAttribute' => ['process_ors_id' => 'id']],
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
            'serial_number' => 'Serial Number',
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
    public function getRecordAllotmentEntries()
    {
        return $this->hasOne(RecordAllotmentEntries::class, ['id' => 'record_allotment_entries_id']);
    }
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                if (empty($this->serial_number)) {

                    $this->serial_number = $this->generateSerialNumber();
                }
            }
            return true;
        }
        return false;
    }
    private function generateSerialNumber()
    {
        $period = DateTime::createFromFormat('Y-m', $this->reporting_period);
        $yr = $period->format('Y');
        $lastNum = ProcessOrsEntries::find()
            ->addSelect([
                new Expression("CAST(SUBSTRING_INDEX(serial_number,'-',-1) AS UNSIGNED) as last_num")
            ])
            ->andWhere("reporting_period LIKE :yr", ['yr' => $yr . '%'])
            ->orderBy('last_num DESC')
            ->limit(1)
            ->scalar();
        $num = !empty($lastNum) ? intval($lastNum) + 1 : 1;

        return $yr . '-' . str_pad($num, 4, '0', STR_PAD_LEFT);
    }
}
