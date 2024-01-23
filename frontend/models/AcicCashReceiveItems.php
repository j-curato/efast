<?php

namespace app\models;

use Yii;
use app\behaviors\HistoryLogsBehavior;

/**
 * This is the model class for table "acic_cash_receive_items".
 *
 * @property int $id
 * @property int $fk_acic_id
 * @property int $fk_cash_receive_id
 * @property float $amount
 * @property int|null $is_deleted
 * @property string|null $deleted_at
 * @property string $created_at
 *
 * @property Acics $fkAcic
 * @property CashReceived $fkCashReceive
 */
class AcicCashReceiveItems extends \yii\db\ActiveRecord
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
        return 'acic_cash_receive_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_acic_id', 'fk_cash_receive_id', 'amount'], 'required'],
            [['fk_acic_id', 'fk_cash_receive_id', 'is_deleted'], 'integer'],
            [['amount'], 'number'],
            [['deleted_at', 'created_at'], 'safe'],
            [['fk_acic_id'], 'exist', 'skipOnError' => true, 'targetClass' => Acics::className(), 'targetAttribute' => ['fk_acic_id' => 'id']],
            [['fk_cash_receive_id'], 'exist', 'skipOnError' => true, 'targetClass' => CashReceived::className(), 'targetAttribute' => ['fk_cash_receive_id' => 'id']],
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
            'fk_cash_receive_id' => 'Fk Cash Receive ID',
            'amount' => 'Amount',
            'is_deleted' => 'Is Deleted',
            'deleted_at' => 'Deleted At',
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
     * Gets query for [[FkCashReceive]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkCashReceive()
    {
        return $this->hasOne(CashReceived::className(), ['id' => 'fk_cash_receive_id']);
    }
}
