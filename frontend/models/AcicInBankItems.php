<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "acic_in_bank_items".
 *
 * @property int $id
 * @property int $fk_acic_in_bank_id
 * @property int $fk_acic_id
 * @property string $created_at
 *
 * @property Acics $fkAcic
 * @property AcicInBank $fkAcicInBank
 */
class AcicInBankItems extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'acic_in_bank_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_acic_in_bank_id', 'fk_acic_id'], 'required'],
            [['fk_acic_in_bank_id', 'fk_acic_id'], 'integer'],
            [['created_at'], 'safe'],
            [['fk_acic_id'], 'exist', 'skipOnError' => true, 'targetClass' => Acics::class, 'targetAttribute' => ['fk_acic_id' => 'id']],
            [['fk_acic_in_bank_id'], 'exist', 'skipOnError' => true, 'targetClass' => AcicInBank::class, 'targetAttribute' => ['fk_acic_in_bank_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_acic_in_bank_id' => 'Fk Acic In Bank',
            'fk_acic_id' => 'Fk Acic ID',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[FkAcic]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkAcic()
    {
        return $this->hasOne(Acics::class, ['id' => 'fk_acic_id']);
    }

    /**
     * Gets query for [[FkAcicInBank]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkAcicInBank()
    {
        return $this->hasOne(AcicInBank::class, ['id' => 'fk_acic_in_bank_id']);
    }
}
