<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "remittance_payee".
 *
 * @property int $id
 * @property int $payee_id
 * @property string $object_code
 */
class RemittancePayee extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'remittance_payee';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['payee_id', 'object_code'], 'required'],
            [['payee_id'], 'integer'],
            [['object_code'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'payee_id' => 'Payee ID',
            'object_code' => 'Object Code',
        ];
    }
    public function getPayee()
    {
        return $this->hasOne(Payee::class, ['id' => 'payee_id']);
    }
    public function getGeneralLedger()
    {

        return $this->hasOne(ChartOfAccounts::class, ['uacs'=> 'object_code']);
    }
}
