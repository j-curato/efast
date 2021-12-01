<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%dv_aucs_entries}}".
 *
 * @property int $id
 * @property int $dv_aucs_id
 * @property int|null $raoud_id
 * @property float|null $amount_disbursed
 * @property float|null $vat_nonvat
 * @property float|null $ewt_goods_services
 * @property float|null $compensation
 * @property float|null $other_trust_liabilities
 * @property float|null $total_withheld
 * @property int|null $process_ors_id
 *
 * @property DvAucs $dvAucs
 * @property Raouds $raoud
 */
class DvAucsEntries extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%dv_aucs_entries}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dv_aucs_id'], 'required'],
            [['dv_aucs_id', 'raoud_id', 'process_ors_id'], 'integer'],
            [['amount_disbursed', 'vat_nonvat', 'ewt_goods_services', 'compensation', 'other_trust_liabilities', 'total_withheld'], 'number'],
            [['dv_aucs_id'], 'exist', 'skipOnError' => true, 'targetClass' => DvAucs::className(), 'targetAttribute' => ['dv_aucs_id' => 'id']],
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
            'dv_aucs_id' => 'Dv Aucs ID',
            'raoud_id' => 'Raoud ID',
            'amount_disbursed' => 'Amount Disbursed',
            'vat_nonvat' => 'Vat Nonvat',
            'ewt_goods_services' => 'Ewt Goods Services',
            'compensation' => 'Compensation',
            'other_trust_liabilities' => 'Other Trust Liabilities',
            'total_withheld' => 'Total Withheld',
            'process_ors_id' => 'Process Ors ID',
        ];
    }

    /**
     * Gets query for [[DvAucs]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\DvAucsQuery
     */
    public function getDvAucs()
    {
        return $this->hasOne(DvAucs::className(), ['id' => 'dv_aucs_id']);
    }

    /**
     * Gets query for [[Raoud]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\RaoudsQuery
     */
    public function getRaoud()
    {
        return $this->hasOne(Raouds::className(), ['id' => 'raoud_id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\DvAucsEntriesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\DvAucsEntriesQuery(get_called_class());
    }
}
