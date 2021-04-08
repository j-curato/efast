<?php

namespace common\modules\auth\models;

use Yii;

/**
 * This is the model class for table "dv_aucs_entries".
 *
 * @property int $id
 * @property int $dv_aucs_id
 * @property int $raoud_id
 * @property float|null $total_withheld
 * @property float|null $tax_withheld
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
        return 'dv_aucs_entries';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dv_aucs_id', 'raoud_id'], 'required'],
            [['dv_aucs_id', 'raoud_id'], 'integer'],
            [['total_withheld', 'tax_withheld'], 'number'],
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
            'total_withheld' => 'Total Withheld',
            'tax_withheld' => 'Tax Withheld',
        ];
    }

    /**
     * Gets query for [[DvAucs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDvAucs()
    {
        return $this->hasOne(DvAucs::className(), ['id' => 'dv_aucs_id']);
    }

    /**
     * Gets query for [[Raoud]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRaoud()
    {
        return $this->hasOne(Raouds::className(), ['id' => 'raoud_id']);
    }
}
