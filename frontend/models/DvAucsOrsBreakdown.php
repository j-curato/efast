<?php

namespace app\models;

use app\behaviors\HistoryLogsBehavior;
use Yii;

/**
 * This is the model class for table "tbl_dv_aucs_ors_breakdown".
 *
 * @property int $id
 * @property int $fk_dv_aucs_id
 * @property int $fk_process_ors_entry_id
 * @property float|null $amount_disbursed
 * @property float|null $vat_nonvat
 * @property float|null $ewt_goods_services
 * @property float|null $compensation
 * @property float|null $other_trust_liabilities
 * @property float|null $total_withheld
 * @property float|null $process_ors_id
 * @property float|null $liquidation_damage
 * @property float|null $tax_portion_of_post
 * @property int|null $is_deleted
 *
 * @property DvAucs $fkDvAucs
 * @property ProcessOrsEntries $fkProcessOrsEntry
 */
class DvAucsOrsBreakdown extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            HistoryLogsBehavior::class,
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_dv_aucs_ors_breakdown';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_dv_aucs_id', 'fk_process_ors_entry_id'], 'required'],
            [['fk_dv_aucs_id', 'fk_process_ors_entry_id', 'is_deleted'], 'integer'],
            [['amount_disbursed', 'vat_nonvat', 'ewt_goods_services', 'compensation', 'other_trust_liabilities',  'liquidation_damage', 'tax_portion_of_post'], 'number'],
            [['fk_dv_aucs_id'], 'exist', 'skipOnError' => true, 'targetClass' => DvAucs::class, 'targetAttribute' => ['fk_dv_aucs_id' => 'id']],
            [['fk_process_ors_entry_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProcessOrsEntries::class, 'targetAttribute' => ['fk_process_ors_entry_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_dv_aucs_id' => ' Dv Aucs ',
            'fk_process_ors_entry_id' => ' Process Ors Entry ',
            'amount_disbursed' => 'Amount Disbursed',
            'vat_nonvat' => 'Vat Nonvat',
            'ewt_goods_services' => 'Ewt Goods Services',
            'compensation' => 'Compensation',
            'other_trust_liabilities' => 'Other Trust Liabilities',
            'liquidation_damage' => 'Liquidation Damage',
            'tax_portion_of_post' => 'Tax Portion Of Post',
            'is_deleted' => 'Is Deleted',
        ];
    }

    /**
     * Gets query for [[FkDvAucs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkDvAucs()
    {
        return $this->hasOne(DvAucs::class, ['id' => 'fk_dv_aucs_id']);
    }

    /**
     * Gets query for [[FkProcessOrsEntry]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkProcessOrsEntry()
    {
        return $this->hasOne(ProcessOrsEntries::class, ['id' => 'fk_process_ors_entry_id']);
    }
}
