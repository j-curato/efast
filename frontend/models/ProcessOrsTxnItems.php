<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%process_ors_txn_items}}".
 *
 * @property int $id
 * @property int|null $fk_process_ors_id
 * @property int|null $fk_transaction_item_id
 * @property float|null $amount
 * @property int|null $is_deleted
 * @property string $created_at
 *
 * @property ProcessOrs $fkProcessOrs
 * @property TransactionItems $fkTransactionItem
 */
class ProcessOrsTxnItems extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%process_ors_txn_items}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_process_ors_id', 'fk_transaction_item_id', 'is_deleted'], 'integer'],
            [['amount'], 'number'],
            [['created_at'], 'safe'],
            [['fk_process_ors_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProcessOrs::class, 'targetAttribute' => ['fk_process_ors_id' => 'id']],
            [['fk_transaction_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => TransactionItems::class, 'targetAttribute' => ['fk_transaction_item_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_process_ors_id' => 'Fk Process Ors ID',
            'fk_transaction_item_id' => 'Fk Transaction Item ID',
            'amount' => 'Amount',
            'is_deleted' => 'Is Deleted',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[FkProcessOrs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkProcessOrs()
    {
        return $this->hasOne(ProcessOrs::class, ['id' => 'fk_process_ors_id']);
    }

    /**
     * Gets query for [[FkTransactionItem]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkTransactionItem()
    {
        return $this->hasOne(TransactionItems::class, ['id' => 'fk_transaction_item_id']);
    }
}
