<?php

namespace app\models;

use app\behaviors\GenerateIdBehavior;
use app\behaviors\HistoryLogsBehavior;
use Yii;

/**
 * This is the model class for table "tbl_rfq_mfos".
 *
 * @property int $id
 * @property int|null $fk_rfq_id
 * @property int|null $fk_mfo_pap_code_id
 * @property int|null $is_deleted
 *
 * @property MfoPapCode $fkMfoPapCode
 * @property PrRfq $fkRfq
 */
class RfqMfos extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            HistoryLogsBehavior::class,
            GenerateIdBehavior::class,
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_rfq_mfos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'fk_rfq_id', 'fk_mfo_pap_code_id', 'is_deleted'], 'integer'],
            [['id'], 'unique'],
            [['fk_mfo_pap_code_id'], 'exist', 'skipOnError' => true, 'targetClass' => MfoPapCode::class, 'targetAttribute' => ['fk_mfo_pap_code_id' => 'id']],
            [['fk_rfq_id'], 'exist', 'skipOnError' => true, 'targetClass' => PrRfq::class, 'targetAttribute' => ['fk_rfq_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_rfq_id' => 'Fk Rfq ID',
            'fk_mfo_pap_code_id' => 'Fk Mfo Pap Code ID',
            'is_deleted' => 'Is Deleted',
        ];
    }

    /**
     * Gets query for [[FkMfoPapCode]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMfoPapCode()
    {
        return $this->hasOne(MfoPapCode::class, ['id' => 'fk_mfo_pap_code_id']);
    }

    /**
     * Gets query for [[FkRfq]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkRfq()
    {
        return $this->hasOne(PrRfq::class, ['id' => 'fk_rfq_id']);
    }
}
