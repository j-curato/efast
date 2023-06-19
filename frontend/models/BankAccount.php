<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bank_account".
 *
 * @property int $id
 * @property string $account_number
 * @property string|null $province
 * @property string $created_at
 */
class BankAccount extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bank_account';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'account_number',
                'account_name',
                'fk_office_id'
            ], 'required'],
            [['fk_office_id'], 'integer'],
            [['created_at', 'account_name'], 'safe'],
            [['account_number', 'province'], 'string', 'max' => 255],
            [[
                'account_number',
                'account_name',
                'province',
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
            'account_number' => 'Account Number',
            'account_name' => 'Account Name',
            'province' => 'Province',
            'created_at' => 'Created At',
            'fk_office_id' => 'Office',

        ];
    }
}
