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
    public $isMaf = false;
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
        $rules =  [
            [['record_allotment_id', 'chart_of_account_id', 'amount'], 'required'],
            [[
                'record_allotment_id',
                'chart_of_account_id',
                'is_deleted',
                'fk_office_id',
                'fk_division_id'

            ], 'integer'],
            [['amount'], 'number'],

        ];

        if ($this->isMaf) {
            $rules[] = [[
                'fk_office_id',
                'fk_division_id'
            ], 'required'];
        }
        return $rules;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'record_allotment_id' => 'Record Allotment ID',
            'chart_of_account_id' => 'Chart Of Account ',
            'amount' => 'Amount',
            'is_deleted' => 'isDeleted',
            'fk_office_id' => 'Office',
            'fk_division_id' => 'Division'


        ];
    }
    public function beforeValidate()
    {
        // echo $this->recordAllotment->isMaf;
        // die();
        if ($this->isMaf) {
            if (empty($this->fk_office_id)) {
                $this->addError('office', 'Office Cannot Be Blank');
            }
            if (empty($this->fk_division_id)) {
                $this->addError('office', 'Division Cannot Be Blank');
            }
        }
        return true;
    }

    public function getChartOfAccount()
    {
        return $this->hasOne(ChartOfAccounts::class, ['id' => 'chart_of_account_id']);
    }
    public function getRecordAllotment()
    {
        return $this->hasOne(RecordAllotments::class, ['id' => 'record_allotment_id']);
    }
    public function getProcessOrsEntries()
    {
        return $this->hasMany(ProcessOrsEntries::class, ['record_allotment_entries_id' => 'id']);
    }
}
