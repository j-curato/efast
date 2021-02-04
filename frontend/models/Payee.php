<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "payee".
 *
 * @property int $id
 * @property string $account_name
 * @property string $registered_name
 * @property string $contact_person
 * @property string $registered_address
 * @property string $contact
 * @property string $remark
 * @property string $tin_number
 *
 * @property Transaction[] $transactions
 */
class Payee extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payee';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['account_name', 'registered_name', 'contact_person', 'registered_address', 'contact', 'remark', 'tin_number'], 'required'],
            [['account_name', 'registered_name', 'contact_person', 'registered_address', 'remark'], 'string', 'max' => 255],
            [['contact'], 'string', 'max' => 20],
            [['tin_number'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'account_name' => 'Account Name',
            'registered_name' => 'Registered Name',
            'contact_person' => 'Contact Person',
            'registered_address' => 'Registered Address',
            'contact' => 'Contact',
            'remark' => 'Remark',
            'tin_number' => 'Tin Number',
        ];
    }

    /**
     * Gets query for [[Transactions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTransactions()
    {
        return $this->hasMany(Transaction::className(), ['payee_id' => 'id']);
    }
}
