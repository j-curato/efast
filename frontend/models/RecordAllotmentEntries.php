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
}
