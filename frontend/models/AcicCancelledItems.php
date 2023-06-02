<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "acic_cancelled_items".
 *
 * @property int $id
 * @property int $fk_acic_id
 * @property int $fk_cash_disbursement_id
 * @property string $created_at
 *
 * @property Acics $fkAcic
 * @property CashDisbursement $fkCashDisbursement
 */
class AcicCancelledItems extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'acic_cancelled_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_acic_id', 'fk_cash_disbursement_id'], 'required'],
            [['fk_acic_id', 'fk_cash_disbursement_id'], 'integer'],
            [['created_at'], 'safe'],
            [['fk_acic_id'], 'exist', 'skipOnError' => true, 'targetClass' => Acics::className(), 'targetAttribute' => ['fk_acic_id' => 'id']],
            [['fk_cash_disbursement_id'], 'exist', 'skipOnError' => true, 'targetClass' => CashDisbursement::className(), 'targetAttribute' => ['fk_cash_disbursement_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_acic_id' => 'Fk Acic ID',
            'fk_cash_disbursement_id' => 'Fk Cash Disbursement ID',
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
        return $this->hasOne(Acics::className(), ['id' => 'fk_acic_id']);
    }

    /**
     * Gets query for [[FkCashDisbursement]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkCashDisbursement()
    {
        return $this->hasOne(CashDisbursement::className(), ['id' => 'fk_cash_disbursement_id']);
    }
}
