<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "chart_of_accounts".
 *
 * @property int $id
 * @property string $uacs
 * @property string $general_ledger
 * @property int $major_account_id
 * @property int $sub_major_account
 * @property string $account_group
 * @property string $current_noncurrent
 * @property string $enable_disable
 *
 * @property MajorAccounts $majorAccount
 * @property SubMajorAccounts $subMajorAccount
 * @property JevPreparation[] $jevPreparations
 */
class ChartOfAccounts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'chart_of_accounts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uacs', 'general_ledger', 'major_account_id', 'sub_major_account', 'account_group', 'current_noncurrent', 'enable_disable'], 'required'],
            [['major_account_id', 'sub_major_account'], 'integer'],
            [['uacs'], 'string', 'max' => 30],
            [['general_ledger', 'account_group', 'current_noncurrent', 'enable_disable'], 'string', 'max' => 255],
            [['major_account_id'], 'exist', 'skipOnError' => true, 'targetClass' => MajorAccounts::className(), 'targetAttribute' => ['major_account_id' => 'id']],
            [['sub_major_account'], 'exist', 'skipOnError' => true, 'targetClass' => SubMajorAccounts::className(), 'targetAttribute' => ['sub_major_account' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uacs' => 'Uacs',
            'general_ledger' => 'General Ledger',
            'major_account_id' => 'Major Account ID',
            'sub_major_account' => 'Sub Major Account',
            'account_group' => 'Account Group',
            'current_noncurrent' => 'Current Noncurrent',
            'enable_disable' => 'Enable Disable',
        ];
    }

    /**
     * Gets query for [[MajorAccount]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMajorAccount()
    {
        return $this->hasOne(MajorAccounts::className(), ['id' => 'major_account_id']);
    }

    /**
     * Gets query for [[SubMajorAccount]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubMajorAccount()
    {
        return $this->hasOne(SubMajorAccounts::className(), ['id' => 'sub_major_account']);
    }

    /**
     * Gets query for [[JevPreparations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJevPreparations()
    {
        return $this->hasMany(JevPreparation::className(), ['chart_of_accounts_id' => 'id']);
    }
    public function getSubAccounts1()
    {
        return $this->hasMany(SubAccounts1::className(), ['chart_of_account_id' => 'id']);
    }
}
