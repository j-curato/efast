<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "record_allotment_entries".
 *
 * @property int $id
 * @property int $record_allotment_id
 * @property int $chart_of_account_id
 * @property float|null $amount
 */
class RecordAllotmentEntries extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'record_allotment_entries';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['record_allotment_id', 'chart_of_account_id'], 'required'],
            [['record_allotment_id', 'chart_of_account_id'], 'integer'],
            [['amount'], 'number'],
            [[
                'id',
                'record_allotment_id',
                'chart_of_account_id',
                'amount',
                'lvl',
                'object_code',
                'report_type',

            ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
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
            'chart_of_account_id' => 'Chart Of Account ID',
            'amount' => 'Amount',
        ];
    }

    public function getChartOfAccount()
    {
        return $this->hasOne(ChartOfAccounts::class, ['id' => 'chart_of_account_id']);
    }
    public function getRecordAllotment()
    {
        return $this->hasOne(RecordAllotments::class, ['id' => 'record_allotment_id']);
    }

    public function getRaouds()
    {
        return $this->hasMany(Raouds::class, ['record_allotment_entries_id' => 'id']);
    }
    public function getProcessOrsEntries()
    {
        return $this->hasMany(ProcessOrsEntries::class, ['record_allotment_entries_id' => 'id']);
    }
}
