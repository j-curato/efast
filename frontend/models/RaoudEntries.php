<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "raoud_entries".
 *
 * @property int $id
 * @property int|null $raoud_id
 * @property int|null $chart_of_account_id
 * @property float|null $amount
 *
 * @property ChartOfAccounts $chartOfAccount
 * @property Raouds $raoud
 */
class RaoudEntries extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'raoud_entries';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['raoud_id', 'chart_of_account_id'], 'integer'],
            [['amount'], 'number'],
            [['chart_of_account_id'], 'exist', 'skipOnError' => true, 'targetClass' => ChartOfAccounts::className(), 'targetAttribute' => ['chart_of_account_id' => 'id']],
            [['raoud_id'], 'exist', 'skipOnError' => true, 'targetClass' => Raouds::className(), 'targetAttribute' => ['raoud_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'raoud_id' => 'Raoud ID',
            'chart_of_account_id' => 'Chart Of Account ID',
            'amount' => 'Amount',
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
     * Gets query for [[Raoud]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRaoud()
    {
        return $this->hasOne(Raouds::className(), ['id' => 'raoud_id']);
    }
}
