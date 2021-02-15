<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "jev_accounting_entries".
 *
 * @property int $id
 * @property int $jev_preparation_id
 * @property int $chart_of_account_id
 * @property float $debit
 * @property float $credit
 *
 * @property ChartOfAccounts $chartOfAccount
 * @property JevPreparation $jevPreparation
 */
class JevAccountingEntries extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'jev_accounting_entries';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[ 'chart_of_account_id', 'debit', 'credit'], 'required'],
            [['jev_preparation_id', 'chart_of_account_id'], 'integer'],
            [['debit', 'credit'], 'number'],
            [['chart_of_account_id'], 'exist', 'skipOnError' => true, 'targetClass' => ChartOfAccounts::className(), 'targetAttribute' => ['chart_of_account_id' => 'id']],
            [['jev_preparation_id'], 'exist', 'skipOnError' => true, 'targetClass' => JevPreparation::className(), 'targetAttribute' => ['jev_preparation_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'jev_preparation_id' => 'Jev Preparation ID',
            'chart_of_account_id' => 'Chart Of Account ID',
            'debit' => 'Debit',
            'credit' => 'Credit',
        ];
    }
    /**
     * Gets query for [[ChartOfAccount]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getChartOfAccount()
    {
        return $this->hasOne(ChartOfAccounts::className(), ['id' => 'chart_of_account_id']);
    }

    /**
     * Gets query for [[JevPreparation]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJevPreparation()
    {
        return $this->hasOne(JevPreparation::className(), ['id' => 'jev_preparation_id']);
    }

}
