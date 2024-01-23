<?php

namespace app\models;

use Yii;
use app\behaviors\HistoryLogsBehavior;

/**
 * This is the model class for table "rci_items".
 *
 * @property int $id
 * @property int $fk_rci_id
 * @property int $fk_cash_disbursement_id
 * @property int $is_deleted
 * @property string|null $deleted_at
 * @property string $created_at
 *
 * @property CashDisbursement $fkCashDisbursementItem
 * @property Rci $fkRci
 */
class RciItems extends \yii\db\ActiveRecord
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
        return 'rci_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_rci_id', 'fk_cash_disbursement_id'], 'required'],
            [['fk_rci_id', 'fk_cash_disbursement_id', 'is_deleted'], 'integer'],
            [['deleted_at', 'created_at'], 'safe'],
            [['fk_cash_disbursement_id'], 'exist', 'skipOnError' => true, 'targetClass' => CashDisbursement::class, 'targetAttribute' => ['fk_cash_disbursement_id' => 'id']],
            [['fk_rci_id'], 'exist', 'skipOnError' => true, 'targetClass' => Rci::class, 'targetAttribute' => ['fk_rci_id' => 'id']],
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
            'fk_cash_disbursement_id' => 'Fk Cash Disbursement Item ID',
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
        return $this->hasOne(CashDisbursement::class, ['id' => 'fk_cash_disbursement_id']);
    }

    /**
     * Gets query for [[FkRci]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkRci()
    {
        return $this->hasOne(Rci::class, ['id' => 'fk_rci_id']);
    }
}
