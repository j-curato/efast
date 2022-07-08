<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "payroll_items".
 *
 * @property int $id
 * @property int $payroll_id
 * @property string $object_code
 * @property float|null $amount
 * @property int|null $remittance_payee_id
 * @property string $created_at
 */
class PayrollItems extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payroll_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['payroll_id', 'object_code'], 'required'],
            [['payroll_id', 'remittance_payee_id'], 'integer'],
            [['amount'], 'number'],
            [['created_at'], 'safe'],
            [['object_code'], 'string', 'max' => 255],
            [[
                'id',
                'payroll_id',
                'object_code',
                'amount',
                'remittance_payee_id',
                'created_at',
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
            'payroll_id' => 'Payroll ID',
            'object_code' => 'Object Code',
            'amount' => 'Amount',
            'remittance_payee_id' => 'Remittance Payee ID',
            'created_at' => 'Created At',
        ];
    }
}
