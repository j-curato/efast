<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "liquidation".
 *
 * @property int $id
 * @property int|null $payee_id
 * @property int|null $responsibility_center_id
 * @property string|null $check_date
 * @property string|null $check_number
 * @property string|null $dv_number
 * @property string|null $particular
 *
 * @property Payee $payee
 * @property ResponsibilityCenter $responsibilityCenter
 * @property LiquidationEntries[] $liquidationEntries
 */
class Liquidation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'liquidation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['payee_id', 'responsibility_center_id'], 'integer'],
            [['particular'], 'string'],
            [['check_date', 'check_number'], 'string', 'max' => 50],
            [['reporting_period'], 'string', 'max' => 20],
            [['dv_number'], 'string', 'max' => 100],
            [['payee_id'], 'exist', 'skipOnError' => true, 'targetClass' => Payee::class, 'targetAttribute' => ['payee_id' => 'id']],
            [['responsibility_center_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResponsibilityCenter::class, 'targetAttribute' => ['responsibility_center_id' => 'id']],
            [['chart_of_account_id', 'advances_entries_id'], 'integer'],
            [['withdrawals', 'vat_nonvat', 'ewt_goods_services'], 'number'],
            [['advances_entries_id'], 'exist', 'skipOnError' => true, 'targetClass' => Advances::class, 'targetAttribute' => ['advances_entries_id' => 'id']],
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
            'payee_id' => 'Payee ID',
            'responsibility_center_id' => 'Responsibility Center ID',
            'check_date' => 'Check Date',
            'check_number' => 'Check Number',
            'dv_number' => 'Dv Number',
            'particular' => 'Particular',
            'reporting_period' => 'Reporting Period',
            'chart_of_account_id' => 'Chart Of Account ID',
            'advances_entries_id' => 'Advances ID',
            'withdrawals' => 'Withdrawals',
            'vat_nonvat' => 'Vat Nonvat',
            'ewt_goods_services' => 'Ewt Goods Services',
        ];
    }

    /**
     * Gets query for [[Payee]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPayee()
    {
        return $this->hasOne(Payee::class, ['id' => 'payee_id']);
    }

    /**
     * Gets query for [[ResponsibilityCenter]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResponsibilityCenter()
    {
        return $this->hasOne(ResponsibilityCenter::class, ['id' => 'responsibility_center_id']);
    }

    /**
     * Gets query for [[LiquidationEntries]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLiquidationEntries()
    {
        return $this->hasMany(LiquidationEntries::class, ['liquidation_id' => 'id']);
    }
    public function getChartOfAccount()
    {
        return $this->hasOne(ChartOfAccounts::class, ['id' => 'chart_of_account_id']);
    }
}
