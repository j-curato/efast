<?php

namespace app\models;

use Yii;
use app\behaviors\GenerateIdBehavior;
use app\behaviors\HistoryLogsBehavior;

/**
 * This is the model class for table "tbl_fmi_lgu_liquidation_items".
 *
 * @property int $id
 * @property int|null $fk_fmi_lgu_liquidation_id
 * @property string|null $reporting_period
 * @property string $date
 * @property string $check_number
 * @property string $payee
 * @property string|null $particular
 * @property float|null $grant_amount
 * @property float|null $equity_amount
 * @property float|null $other_fund_amount
 * @property int|null $is_deleted
 * @property string $created_at
 *
 * @property FmiLguLiquidations $fkFmiLguLiquidation
 */
class FmiLguLiquidationItems extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            'history' => [
                'class' => HistoryLogsBehavior::class
            ],
            'generateId' => [
                'class' => GenerateIdBehavior::class
            ]
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_fmi_lgu_liquidation_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'date',
                'check_number',
                'payee',
                'particular',
                'reporting_period',
            ], 'required'],
            [['id', 'fk_fmi_lgu_liquidation_id', 'is_deleted'], 'integer'],
            [['date', 'created_at'], 'safe'],
            [['particular'], 'string'],
            [['grant_amount', 'equity_amount', 'other_fund_amount'], 'number'],
            [['grant_amount', 'equity_amount', 'other_fund_amount'], 'default', 'value' => 0],
            [['reporting_period', 'check_number', 'payee'], 'string', 'max' => 255],
            [['id'], 'unique'],
            [['fk_fmi_lgu_liquidation_id'], 'exist', 'skipOnError' => true, 'targetClass' => FmiLguLiquidations::class, 'targetAttribute' => ['fk_fmi_lgu_liquidation_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_fmi_lgu_liquidation_id' => ' LGU Liquidation ',
            'reporting_period' => 'Reporting Period',
            'date' => 'Date',
            'check_number' => 'Check Number',
            'payee' => 'Payee',
            'particular' => 'Particular',
            'grant_amount' => 'Grant Amount',
            'equity_amount' => 'Equity Amount',
            'other_fund_amount' => 'Other Fund Amount',
            'is_deleted' => 'Is Deleted',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[FkFmiLguLiquidation]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkFmiLguLiquidation()
    {
        return $this->hasOne(FmiLguLiquidations::class, ['id' => 'fk_fmi_lgu_liquidation_id']);
    }
}
