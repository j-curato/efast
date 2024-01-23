<?php

namespace app\models;

use Yii;
use app\behaviors\HistoryLogsBehavior;

/**
 * This is the model class for table "ro_liquidation_report".
 *
 * @property int $id
 * @property string $liquidation_report_number
 * @property string|null $date
 * @property string|null $updated_at
 * @property string $created_at
 *
 * @property RoLiquidationReportItems[] $roLiquidationReportItems
 * @property RoLiquidationReportRefunds[] $roLiquidationReportRefunds
 */
class RoLiquidationReport extends \yii\db\ActiveRecord
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
        return 'ro_liquidation_report';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'liquidation_report_number', 'fk_dv_aucs_id', 'reporting_period', 'date'], 'required'],
            [['id', 'f_dv_aucs_id'], 'integer'],
            [['date', 'updated_at', 'created_at', 'reporting_period'], 'safe'],
            [['liquidation_report_number'], 'string', 'max' => 255],
            [['liquidation_report_number'], 'unique'],
            [['id'], 'unique'],
            [[
                'id',
                'liquidation_report_number',
                'date',
                'reporting_period',
                'updated_at',
                'created_at',
                'fk_dv_aucs_id',

            ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'liquidation_report_number' => 'Liquidation Report Number',
            'date' => 'Date',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
            'reporting_period' => 'Reporting Period',
            'fk_dv_aucs_id' => 'DV Aucs',
        ];
    }

    /**
     * Gets query for [[RoLiquidationReportItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoLiquidationReportItems()
    {
        return $this->hasMany(RoLiquidationReportItems::class, ['fk_ro_liquidation_report_id' => 'id']);
    }

    /**
     * Gets query for [[RoLiquidationReportRefunds]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoLiquidationReportRefunds()
    {
        return $this->hasMany(RoLiquidationReportRefunds::class, ['fk_ro_liquidation_report_id' => 'id']);
    }
}
