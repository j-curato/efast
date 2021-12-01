<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%chart_of_accounts}}".
 *
 * @property int $id
 * @property string $uacs
 * @property string $general_ledger
 * @property int $major_account_id
 * @property int $sub_major_account
 * @property int $sub_major_account_2_id
 * @property string $account_group
 * @property string $current_noncurrent
 * @property string $enable_disable
 * @property string|null $normal_balance
 * @property int|null $is_active
 *
 * @property MajorAccounts $majorAccount
 * @property SubMajorAccounts $subMajorAccount
 * @property DvAccountingEntries[] $dvAccountingEntries
 * @property JevAccountingEntries[] $jevAccountingEntries
 * @property LiquidationEntries[] $liquidationEntries
 * @property ProcessOrsEntries[] $processOrsEntries
 * @property RaoudEntries[] $raoudEntries
 * @property RecordAllotmentEntries[] $recordAllotmentEntries
 * @property SubAccounts1[] $subAccounts1s
 */
class ChartOfAccounts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%chart_of_accounts}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uacs', 'general_ledger', 'major_account_id', 'sub_major_account', 'sub_major_account_2_id', 'account_group', 'current_noncurrent', 'enable_disable'], 'required'],
            [['major_account_id', 'sub_major_account', 'sub_major_account_2_id', 'is_active'], 'integer'],
            [['uacs'], 'string', 'max' => 30],
            [['general_ledger', 'account_group', 'current_noncurrent', 'enable_disable'], 'string', 'max' => 255],
            [['normal_balance'], 'string', 'max' => 20],
            [['uacs'], 'unique'],
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
            'sub_major_account_2_id' => 'Sub Major Account 2 ID',
            'account_group' => 'Account Group',
            'current_noncurrent' => 'Current Noncurrent',
            'enable_disable' => 'Enable Disable',
            'normal_balance' => 'Normal Balance',
            'is_active' => 'Is Active',
        ];
    }

    /**
     * Gets query for [[MajorAccount]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\MajorAccountsQuery
     */
    public function getMajorAccount()
    {
        return $this->hasOne(MajorAccounts::className(), ['id' => 'major_account_id']);
    }

    /**
     * Gets query for [[SubMajorAccount]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\SubMajorAccountsQuery
     */
    public function getSubMajorAccount()
    {
        return $this->hasOne(SubMajorAccounts::className(), ['id' => 'sub_major_account']);
    }

    /**
     * Gets query for [[DvAccountingEntries]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\DvAccountingEntriesQuery
     */
    public function getDvAccountingEntries()
    {
        return $this->hasMany(DvAccountingEntries::className(), ['chart_of_account_id' => 'id']);
    }

    /**
     * Gets query for [[JevAccountingEntries]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\JevAccountingEntriesQuery
     */
    public function getJevAccountingEntries()
    {
        return $this->hasMany(JevAccountingEntries::className(), ['chart_of_account_id' => 'id']);
    }

    /**
     * Gets query for [[LiquidationEntries]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\LiquidationEntriesQuery
     */
    public function getLiquidationEntries()
    {
        return $this->hasMany(LiquidationEntries::className(), ['chart_of_account_id' => 'id']);
    }

    /**
     * Gets query for [[ProcessOrsEntries]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\ProcessOrsEntriesQuery
     */
    public function getProcessOrsEntries()
    {
        return $this->hasMany(ProcessOrsEntries::className(), ['chart_of_account_id' => 'id']);
    }

    /**
     * Gets query for [[RaoudEntries]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\RaoudEntriesQuery
     */
    public function getRaoudEntries()
    {
        return $this->hasMany(RaoudEntries::className(), ['chart_of_account_id' => 'id']);
    }

    /**
     * Gets query for [[RecordAllotmentEntries]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\RecordAllotmentEntriesQuery
     */
    public function getRecordAllotmentEntries()
    {
        return $this->hasMany(RecordAllotmentEntries::className(), ['chart_of_account_id' => 'id']);
    }

    /**
     * Gets query for [[SubAccounts1s]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\SubAccounts1Query
     */
    public function getSubAccounts1s()
    {
        return $this->hasMany(SubAccounts1::className(), ['chart_of_account_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\ChartOfAccountsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\ChartOfAccountsQuery(get_called_class());
    }
}
