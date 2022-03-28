<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sub_accounts1".
 *
 * @property int $id
 * @property int $chart_of_account_id
 * @property string $object_code
 * @property string $name
 *
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
        return 'sub_accounts1';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['chart_of_account_id', 'object_code', 'name'], 'required'],
            [['chart_of_account_id','is_active'], 'integer'],
            [['object_code', 'name','reporting_period'], 'string', 'max' => 255],
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
            'is_active' => 'Active',
            'reporting_period'=>'Reporting Period'
        ];
    }

    /**
     * Gets query for [[ChartOfAccount]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getChartOfAccount()
    {
        return $this->hasOne(ChartOfAccounts::class, ['id' => 'chart_of_account_id']);
    }

    /**
     * Gets query for [[SubAccounts2s]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubAccounts2()
    {
        return $this->hasMany(SubAccounts2::class, ['sub_accounts1_id' => 'id']);
    }
}
