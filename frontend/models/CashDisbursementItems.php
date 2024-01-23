<?php

namespace app\models;

use Yii;
use app\behaviors\HistoryLogsBehavior;

/**
 * This is the model class for table "cash_disbursement_items".
 *
 * @property int $id
 * @property int|null $fk_cash_disbursement_id
 * @property int|null $fk_chart_of_account_id
 * @property int|null $fk_dv_aucs_id
 * @property int|null $is_deleted
 * @property string $created_at
 *
 * @property CashDisbursement $fkCashDisbursement
 * @property ChartOfAccounts $fkChartOfAccount
 * @property DvAucs $fkDvAucs
 */
class CashDisbursementItems extends \yii\db\ActiveRecord
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
        return 'cash_disbursement_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_cash_disbursement_id', 'fk_chart_of_account_id', 'fk_dv_aucs_id', 'is_deleted'], 'integer'],
            [['fk_cash_disbursement_id', 'fk_chart_of_account_id', 'fk_dv_aucs_id'], 'required'],
            [['created_at'], 'safe'],
            [['fk_cash_disbursement_id'], 'exist', 'skipOnError' => true, 'targetClass' => CashDisbursement::class, 'targetAttribute' => ['fk_cash_disbursement_id' => 'id']],
            [['fk_chart_of_account_id'], 'exist', 'skipOnError' => true, 'targetClass' => ChartOfAccounts::class, 'targetAttribute' => ['fk_chart_of_account_id' => 'id']],
            [['fk_dv_aucs_id'], 'exist', 'skipOnError' => true, 'targetClass' => DvAucs::class, 'targetAttribute' => ['fk_dv_aucs_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_cash_disbursement_id' => 'Fk Cash Disbursement ID',
            'fk_chart_of_account_id' => 'Fk Chart Of Account ID',
            'fk_dv_aucs_id' => 'Fk Dv Aucs ID',
            'is_deleted' => 'Is Deleted',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[FkCashDisbursement]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCashDisbursement()
    {
        return $this->hasOne(CashDisbursement::class, ['id' => 'fk_cash_disbursement_id']);
    }

    /**
     * Gets query for [[FkChartOfAccount]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getChartOfAccount()
    {
        return $this->hasOne(ChartOfAccounts::class, ['id' => 'fk_chart_of_account_id']);
    }

    /**
     * Gets query for [[FkDvAucs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDvAucs()
    {
        return $this->hasOne(DvAucs::class, ['id' => 'fk_dv_aucs_id']);
    }
}
