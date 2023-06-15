<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rci_items".
 *
 * @property int $id
 * @property int $fk_rci_id
 * @property int $fk_cash_disbursement_item_id
 * @property int $is_deleted
 * @property string|null $deleted_at
 * @property string $created_at
 *
 * @property CashDisbursementItems $fkCashDisbursementItem
 * @property Rci $fkRci
 */
class RciItems extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rci_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_rci_id', 'fk_cash_disbursement_item_id'], 'required'],
            [['fk_rci_id', 'fk_cash_disbursement_item_id', 'is_deleted'], 'integer'],
            [['deleted_at', 'created_at'], 'safe'],
            [['fk_cash_disbursement_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => CashDisbursementItems::className(), 'targetAttribute' => ['fk_cash_disbursement_item_id' => 'id']],
            [['fk_rci_id'], 'exist', 'skipOnError' => true, 'targetClass' => Rci::className(), 'targetAttribute' => ['fk_rci_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_rci_id' => 'Fk Rci ID',
            'fk_cash_disbursement_item_id' => 'Fk Cash Disbursement Item ID',
            'is_deleted' => 'Is Deleted',
            'deleted_at' => 'Deleted At',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[FkCashDisbursementItem]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkCashDisbursementItem()
    {
        return $this->hasOne(CashDisbursementItems::className(), ['id' => 'fk_cash_disbursement_item_id']);
    }

    /**
     * Gets query for [[FkRci]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkRci()
    {
        return $this->hasOne(Rci::className(), ['id' => 'fk_rci_id']);
    }
}
