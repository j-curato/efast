<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "acics_cash_items".
 *
 * @property int $id
 * @property int $fk_acic_id
 * @property int|null $is_deleted
 * @property string $created_at
 *
 * @property Acics $fkAccic
 */
class AcicsCashItems extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'acics_cash_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_acic_id'], 'required'],
            [['fk_acic_id', 'is_deleted'], 'integer'],
            [['created_at'], 'safe'],
            [['fk_acic_id'], 'exist', 'skipOnError' => true, 'targetClass' => Acics::class, 'targetAttribute' => ['fk_acic_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_acic_id' => 'Fk Accic ID',
            'is_deleted' => 'Is Deleted',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[FkAccic]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAcic()
    {
        return $this->hasOne(Acics::class, ['id' => 'fk_acic_id']);
    }
    public function getCashDisbursement()
    {
        return $this->hasOne(CashDisbursement::class, ['id' => 'cash_disbursement']);
    }
}
