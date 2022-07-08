<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ro_liquidation_report_items".
 *
 * @property int $id
 * @property int|null $fk_ro_liquidation_report_id
 * @property float|null $amount
 * @property string|null $object_code
 * @property string|null $reporting_period
 * @property string $created_at
 *
 * @property RoLiquidationReport $fkRoLiquidationReport
 */
class RoLiquidationReportItems extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ro_liquidation_report_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_ro_liquidation_report_id', 'is_reimburse'], 'integer'],
            [['amount'], 'number'],
            [['created_at'], 'safe'],
            [['object_code'], 'string', 'max' => 255],
            [['reporting_period'], 'string', 'max' => 20],
            [[
                'id',
                'fk_ro_liquidation_report_id',
                'fk_cash_disbursement_id',
                'amount',
                'object_code',
                'reporting_period',
                'created_at',
                'is_deleted',
                'deleted_at',
                'is_reimburse',

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
            'amount' => 'Amount',
            'object_code' => 'Object Code',
            'reporting_period' => 'Reporting Period',
            'created_at' => 'Created At',
            'is_reimburse' => 'Is Reimburse',
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
