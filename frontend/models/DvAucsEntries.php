<?php

namespace app\models;

use Yii;
use app\behaviors\HistoryLogsBehavior;

/**
 * This is the model class for table "dv_aucs_entries".
 *
 * @property int $id
 * @property int $dv_aucs_id
 * @property int $raoud_id
 * @property float|null $amount_disbursed
 * @property float|null $vat_nonvat
 * @property float|null $ewt_goods_services
 * @property float|null $compensation
 * @property float|null $other_trust_liabilities
 * @property float|null $total_withheld
 *
 * @property DvAucs $dvAucs
 * @property Raouds $raoud
 */
class DvAucsEntries extends \yii\db\ActiveRecord
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
        return 'dv_aucs_entries';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dv_aucs_id',], 'required'],
            [['dv_aucs_id', 'raoud_id'], 'integer'],
            [['amount_disbursed', 'vat_nonvat', 'ewt_goods_services', 'compensation', 'other_trust_liabilities', 'total_withheld'], 'number'],
            [[
                'id',
                'dv_aucs_id',
                'raoud_id',
                'amount_disbursed',
                'vat_nonvat',
                'ewt_goods_services',
                'compensation',
                'other_trust_liabilities',
                'total_withheld',
                'process_ors_id',
            ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
            [['dv_aucs_id'], 'exist', 'skipOnError' => true, 'targetClass' => DvAucs::class, 'targetAttribute' => ['dv_aucs_id' => 'id']],
            [['raoud_id'], 'exist', 'skipOnError' => true, 'targetClass' => Raouds::class, 'targetAttribute' => ['raoud_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dv_aucs_id' => 'Dv Aucs ID',
            'raoud_id' => 'Raoud ID',
            'amount_disbursed' => 'Amount Disbursed',
            'vat_nonvat' => 'Vat Nonvat',
            'ewt_goods_services' => 'Ewt Goods Services',
            'compensation' => 'Compensation',
            'other_trust_liabilities' => 'Other Trust Liabilities',
            'total_withheld' => 'Total Withheld',
        ];
    }

    /**
     * Gets query for [[DvAucs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDvAucs()
    {
        return $this->hasOne(DvAucs::class, ['id' => 'dv_aucs_id']);
    }
    public function getProcessOrs()
    {
        return $this->hasOne(ProcessOrs::class, ['id' => 'process_ors_id']);
    }

    /**
     * Gets query for [[Raoud]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRaoud()
    {
        return $this->hasOne(Raouds::class, ['id' => 'raoud_id']);
    }
}
