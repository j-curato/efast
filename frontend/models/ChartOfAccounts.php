<?php

namespace app\models;

use Yii;
use yii\db\Query;
use app\behaviors\HistoryLogsBehavior;

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
    public function behaviors()
    {
        return [
            HistoryLogsBehavior::class
        ];
    }
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
            [['uacs', 'general_ledger', 'major_account_id', 'sub_major_account', 'account_group', 'current_noncurrent', 'enable_disable', 'normal_balance'], 'required'],
            [[
                'major_account_id', 'sub_major_account', 'is_active',
                'fk_depreciation_id',
                'fk_impairment_id',
                'fk_ppe_useful_life_id'
            ], 'integer'],
            [['uacs', 'normal_balance'], 'string', 'max' => 30],
            [['is_province_visible'], 'integer'],
            [['general_ledger', 'account_group', 'current_noncurrent', 'enable_disable'], 'string', 'max' => 255],
            [[
                'id',
                'uacs',
                'general_ledger',
                'major_account_id',
                'sub_major_account',
                'sub_major_account_2_id',
                'account_group',
                'current_noncurrent',
                'enable_disable',
                'normal_balance',
                'is_active',
                'is_province_visible',
            ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
            [['major_account_id'], 'exist', 'skipOnError' => true, 'targetClass' => MajorAccounts::class, 'targetAttribute' => ['major_account_id' => 'id']],
            [['sub_major_account'], 'exist', 'skipOnError' => true, 'targetClass' => SubMajorAccounts::class, 'targetAttribute' => ['sub_major_account' => 'id']],
        ];
    }

    public static function searchChartOfAccounts($q, $page, $majorAccId)
    {

        $limit = 5;
        $offset = ($page - 1) * $limit;
        $query = new Query();
        $query->select(["id as id, CONCAT (uacs ,'-',general_ledger) as text"])
            ->from('chart_of_accounts')
            ->where(['like', 'general_ledger', $q])
            ->orWhere(['like', 'uacs', $q])
            ->andWhere('is_active = 1');
        if (!empty($majorAccId)) {
            $query->andWhere(['major_account_id' => $majorAccId]);
        }
        if (!empty($page)) {
            $query->offset($offset)
                ->limit($limit);
        }
        $command = $query->createCommand();
        $data = $command->queryAll();
        return $data;
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
            'major_account_id' => 'Major Account ',
            'sub_major_account' => 'Sub Major Account',
            'account_group' => 'Account Group',
            'current_noncurrent' => 'Current Noncurrent',
            'enable_disable' => 'Enable Disable',
            'is_active' => 'Active',
            'is_province_visible' => 'Select  Usable in Province',
            'fk_depreciation_id' => 'Depreciation',
            'fk_impairment_id' => 'Impairment',
            'fk_ppe_useful_life_id' => 'Useful Life'

        ];
    }

    /**
     * Gets query for [[MajorAccount]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMajorAccount()
    {
        return $this->hasOne(MajorAccounts::class, ['id' => 'major_account_id']);
    }

    /**
     * Gets query for [[SubMajorAccount]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubMajorAccount()
    {
        return $this->hasOne(SubMajorAccounts::class, ['id' => 'sub_major_account']);
    }

    /**
     * Gets query for [[JevPreparations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJevPreparations()
    {
        return $this->hasMany(JevPreparation::class, ['chart_of_accounts_id' => 'id']);
    }
    public function getSubAccounts1()
    {
        return $this->hasMany(SubAccounts1::class, ['chart_of_account_id' => 'id']);
    }
    public function getRecordAllotmentEntries()
    {
        return $this->hasMany(RecordAllotmentEntries::class, ['chart_of_account_id' => 'id']);
    }
}
