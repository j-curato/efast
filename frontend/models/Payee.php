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
            [[
                'account_name',
                'fk_bank_id',
                'account_num',
                'fk_office_id',
                'registered_name'
            ], 'required'],
            [['account_name', 'registered_name', 'contact_person', 'registered_address', 'remark'], 'string', 'max' => 255],
            [['contact'], 'string', 'max' => 20],
            [['tin_number'], 'string', 'max' => 30],
            [['account_num'], 'string', 'max' => 255],
            [['isEnable',   'fk_bank_id', 'fk_office_id', 'id'], 'integer',],
            [[
                'account_name',
                'registered_name',
                'contact_person',
                'registered_address',
                'contact',
                'remark',
                'tin_number',
            ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],


        ];
    }
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                if (empty($this->id)) {
                    $this->id = Yii::$app->snowflake->generateId();
                }
            }
            return true;
        }
        return false;
    }
    public function beforeValidate()
    {



        if (parent::beforeValidate()) {
            if (!empty($this->fk_office_id) && !empty($this->registered_name)) {
                $sql = '';
                $params = [];
                if (!empty($this->id)) {
                    $sql = ' AND ';
                    $sql .= Yii::$app->db->getQueryBuilder()->buildCondition(['!=', 'id', $this->id], $params);
                }
                $qry = Yii::$app->db->createCommand("SELECT EXISTS (SELECT   * FROM `payee`
                        WHERE 
                        payee.fk_office_id = :office_id
                        AND payee.registered_name LIKE :_name
                        $sql)", $params)
                    ->bindValue(':office_id', $this->fk_office_id)
                    ->bindValue(':_name', '%' . trim($this->registered_name) . '%')
                    ->queryScalar();
                if ($qry == 1) {
                    $this->addError('registered_name', 'Registered Name Already Exists');
                }
            }
            return true;
        }
        return false;
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
            'isEnable' => 'Enabled',
            'fk_bank_id' => 'Bank',
            'account_num' => 'Account Number',
            'fk_office_id' => 'Office'
        ];
    }

    /**
     * Gets query for [[Transactions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTransactions()
    {
        return $this->hasMany(Transaction::class, ['payee_id' => 'id']);
    }
    public function getPayee()
    {
        return $this->hasMany(JevPreparation::class, ['payee_id' => 'id']);
    }
    public function getBank()
    {
        return $this->hasOne(Banks::class, ['id' => 'fk_bank_id']);
    }
}
