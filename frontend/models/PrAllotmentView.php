<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%pr_allotment_view}}".
 *
 * @property int $allotment_entry_id
 * @property string|null $budget_year
 * @property string|null $office_name
 * @property string|null $division
 * @property string|null $mfo_name
 * @property string|null $fund_source_name
 * @property string|null $account_title
 * @property float $amount
 * @property float $balance
 */
class PrAllotmentView extends \yii\db\ActiveRecord
{
    public $type = '';
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%pr_allotment_view}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['allotment_entry_id'], 'integer'],
            [['amount'], 'required'],
            [['amount', 'balance'], 'number'],
            [['budget_year'], 'string', 'max' => 4],
            [['office_name', 'division', 'mfo_name', 'fund_source_name', 'account_title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'allotment_entry_id' => 'Allotment Entry ID',
            'budget_year' => 'Budget Year',
            'office_name' => 'Office Name',
            'division' => 'Division',
            'mfo_name' => 'Mfo Name',
            'fund_source_name' => 'Fund Source Name',
            'account_title' => 'Account Title',
            'amount' => 'Amount',
            'balance' => 'Balance',
        ];
    }
}
