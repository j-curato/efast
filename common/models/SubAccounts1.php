<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%sub_accounts1}}".
 *
 * @property int $id
 * @property int $chart_of_account_id
 * @property string $object_code
 * @property string $name
 * @property int|null $is_active
 *
 * @property AdvancesEntries[] $advancesEntries
 * @property ChartOfAccounts $chartOfAccount
 * @property SubAccounts2[] $subAccounts2s
 */
class SubAccounts1 extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%sub_accounts1}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['chart_of_account_id', 'object_code', 'name'], 'required'],
            [['chart_of_account_id', 'is_active'], 'integer'],
            [['object_code'], 'string', 'max' => 255],
            [['name'], 'string', 'max' => 500],
            [['object_code'], 'unique'],
            [[
                'id',
                'chart_of_account_id',
                'object_code',
                'name',
                'is_active',
            ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
            [['chart_of_account_id'], 'exist', 'skipOnError' => true, 'targetClass' => ChartOfAccounts::class, 'targetAttribute' => ['chart_of_account_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'chart_of_account_id' => 'Chart Of Account ID',
            'object_code' => 'Object Code',
            'name' => 'Name',
            'is_active' => 'Is Active',
        ];
    }

    /**
     * Gets query for [[AdvancesEntries]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\AdvancesEntriesQuery
     */
    public function getAdvancesEntries()
    {
        return $this->hasMany(AdvancesEntries::class, ['sub_account1_id' => 'id']);
    }

    /**
     * Gets query for [[ChartOfAccount]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\ChartOfAccountsQuery
     */
    public function getChartOfAccount()
    {
        return $this->hasOne(ChartOfAccounts::class, ['id' => 'chart_of_account_id']);
    }

    /**
     * Gets query for [[SubAccounts2s]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\SubAccounts2Query
     */
    public function getSubAccounts2s()
    {
        return $this->hasMany(SubAccounts2::class, ['sub_accounts1_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\SubAccounts1Query the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\SubAccounts1Query(get_called_class());
    }
}
