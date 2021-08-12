<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "record_allotment_for_ors".
 *
 * @property int $id
 * @property string|null $serial_number
 * @property string|null $code
 * @property string|null $mfo_name
 * @property string|null $fund_source_name
 * @property string|null $uacs
 * @property string|null $general_ledger
 * @property float $amount
 * @property float|null $balance
 */
class RecordAllotmentForOrs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'record_allotment_for_ors';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['amount'], 'required'],
            [['amount', 'balance'], 'number'],
            [['serial_number'], 'string', 'max' => 50],
            [['mfo_code', 'mfo_name', 'fund_source_name', 'general_ledger'], 'string', 'max' => 255],
            [['uacs'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'serial_number' => 'Serial Number',
            'mfo_code' => 'Code',
            'mfo_name' => 'Mfo Name',
            'fund_source_name' => 'Fund Source Name',
            'uacs' => 'Uacs',
            'general_ledger' => 'General Ledger',
            'amount' => 'Amount',
            'balance' => 'Balance',
        ];
    }
}
