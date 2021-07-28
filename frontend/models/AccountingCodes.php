<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "accounting_codes".
 *
 * @property string $object_code
 * @property string $account_title
 * @property string $major_object_code
 * @property string $account_group
 * @property string $current_noncurrent
 * @property string $coa_object_code
 * @property string $coa_account_title
 * @property string|null $normal_balance
 */
class AccountingCodes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'accounting_codes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['object_code', 'account_group', 'current_noncurrent', 'coa_account_title'], 'string', 'max' => 255],
            [['account_title'], 'string', 'max' => 500],
            [['major_object_code', 'normal_balance'], 'string', 'max' => 20],
            [['coa_object_code'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'object_code' => 'Object Code',
            'account_title' => 'Account Title',
            'major_object_code' => 'Major Object Code',
            'account_group' => 'Account Group',
            'current_noncurrent' => 'Current Noncurrent',
            'coa_object_code' => 'Coa Object Code',
            'coa_account_title' => 'Coa Account Title',
            'normal_balance' => 'Normal Balance',
        ];
    }
}
