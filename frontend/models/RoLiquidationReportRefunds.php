<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ro_liquidation_report_refunds".
 *
 * @property int $id
 * @property int|null $fk_ro_liquidation_report_id
 * @property string|null $reporting_period
 * @property string|null $or_number
 * @property string|null $or_date
 * @property float|null $amount
 * @property string $created_at
 *
 * @property RoLiquidationReport $fkRoLiquidationReport
 */
class RoLiquidationReportRefunds extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ro_liquidation_report_refunds';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_ro_liquidation_report_id'], 'integer'],
            [['or_date', 'created_at'], 'safe'],
            [['amount'], 'number'],
            [['reporting_period'], 'string', 'max' => 20],
            [['or_number'], 'string', 'max' => 255],
            [[
                'id',
                'fk_ro_liquidation_report_id',
                'fk_cash_disbursement_id',
                'reporting_period',
                'or_number',
                'or_date',
                'amount',
                'created_at',
                'is_deleted',
                'deleted_at',

            ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
            [['fk_ro_liquidation_report_id'], 'exist', 'skipOnError' => true, 'targetClass' => RoLiquidationReport::class, 'targetAttribute' => ['fk_ro_liquidation_report_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_ro_liquidation_report_id' => 'Fk Ro Liquidation Report ID',
            'reporting_period' => 'Reporting Period',
            'or_number' => 'Or Number',
            'or_date' => 'Or Date',
            'amount' => 'Amount',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[FkRoLiquidationReport]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkRoLiquidationReport()
    {
        return $this->hasOne(RoLiquidationReport::class, ['id' => 'fk_ro_liquidation_report_id']);
    }
}
