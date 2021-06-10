<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "liquidation_entries".
 *
 * @property int $id
 * @property int|null $liquidation_id
 * @property int|null $chart_of_account_id
 * @property int|null $advances_id
 * @property float|null $withdrawals
 * @property float|null $vat_nonvat
 * @property float|null $ewt_goods_services
 *
 * @property Advances $advances
 * @property ChartOfAccounts $chartOfAccount
 * @property Liquidation $liquidation
 */
class LiquidationEntries extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'liquidation_entries';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['liquidation_id', 'chart_of_account_id', 'advances_id'], 'integer'],
            [['withdrawals', 'vat_nonvat', 'ewt_goods_services'], 'number'],
            [['advances_id'], 'exist', 'skipOnError' => true, 'targetClass' => Advances::class, 'targetAttribute' => ['advances_id' => 'id']],
            [['chart_of_account_id'], 'exist', 'skipOnError' => true, 'targetClass' => ChartOfAccounts::class, 'targetAttribute' => ['chart_of_account_id' => 'id']],
            [['liquidation_id'], 'exist', 'skipOnError' => true, 'targetClass' => Liquidation::class, 'targetAttribute' => ['liquidation_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'liquidation_id' => 'Liquidation ID',
            'chart_of_account_id' => 'Chart Of Account ID',
            'advances_id' => 'Advances ID',
            'withdrawals' => 'Withdrawals',
            'vat_nonvat' => 'Vat Nonvat',
            'ewt_goods_services' => 'Ewt Goods Services',
        ];
    }

    /**
     * Gets query for [[Advances]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAdvancesEntries()
    {
        return $this->hasOne(AdvancesEntries::class, ['id' => 'advances_id']);
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
     * Gets query for [[Liquidation]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLiquidation()
    {
        return $this->hasOne(Liquidation::class, ['id' => 'liquidation_id']);
    }
}
