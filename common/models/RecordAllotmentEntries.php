<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%record_allotment_entries}}".
 *
 * @property int $id
 * @property int $record_allotment_id
 * @property int $chart_of_account_id
 * @property float $amount
 * @property int|null $lvl
 * @property string|null $object_code
 * @property string|null $report_type
 *
 * @property Raouds[] $raouds
 * @property ChartOfAccounts $chartOfAccount
 * @property RecordAllotments $recordAllotment
 */
class RecordAllotmentEntries extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%record_allotment_entries}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['record_allotment_id', 'chart_of_account_id', 'amount'], 'required'],
            [['record_allotment_id', 'chart_of_account_id', 'lvl'], 'integer'],
            [['amount'], 'number'],
            [['object_code', 'report_type'], 'string', 'max' => 255],
            [[
                'id',
                'record_allotment_id',
                'chart_of_account_id',
                'amount',
                'lvl',
                'object_code',
                'report_type',
            ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
            [['chart_of_account_id'], 'exist', 'skipOnError' => true, 'targetClass' => ChartOfAccounts::class, 'targetAttribute' => ['chart_of_account_id' => 'id']],
            [['record_allotment_id'], 'exist', 'skipOnError' => true, 'targetClass' => RecordAllotments::class, 'targetAttribute' => ['record_allotment_id' => 'id']],
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
            'lvl' => 'Lvl',
            'object_code' => 'Object Code',
            'report_type' => 'Report Type',
        ];
    }

    /**
     * Gets query for [[Raouds]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\RaoudsQuery
     */
    public function getRaouds()
    {
        return $this->hasMany(Raouds::class, ['record_allotment_entries_id' => 'id']);
    }

    /**
     * Gets query for [[ChartOfAccount]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\ChartOfAccountsQuery
     */
    public function getChartOfAccount()
    {
        return $this->hasOne(ChartOfAccounts::class, ['id' => 'chart_of_account_id']);
    }

    /**
     * Gets query for [[RecordAllotment]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\RecordAllotmentsQuery
     */
    public function getRecordAllotment()
    {
        return $this->hasOne(RecordAllotments::class, ['id' => 'record_allotment_id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\RecordAllotmentEntriesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\RecordAllotmentEntriesQuery(get_called_class());
    }
}
