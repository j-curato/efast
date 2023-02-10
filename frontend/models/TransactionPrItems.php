<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%transaction_pr_items}}".
 *
 * @property int $id
 * @property int|null $fk_transaction_id
 * @property int|null $fk_pr_purchase_request_id
 * @property string $created_at
 *
 * @property PrPurchaseRequest $fkPrPurchaseRequest
 * @property Transaction $fkTransaction
 */
class TransactionPrItems extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%transaction_pr_items}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_transaction_id', 'fk_pr_purchase_request_id', 'is_deleted', 'fk_pr_allotment_id'], 'integer'],
            [['created_at'], 'safe'],
            [['amount'], 'number'],
            [['fk_pr_purchase_request_id'], 'exist', 'skipOnError' => true, 'targetClass' => PrPurchaseRequest::class, 'targetAttribute' => ['fk_pr_purchase_request_id' => 'id']],
            [['fk_transaction_id'], 'exist', 'skipOnError' => true, 'targetClass' => Transaction::class, 'targetAttribute' => ['fk_transaction_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_transaction_id' => ' Transaction ID',
            'fk_pr_purchase_request_id' => ' Pr Purchase Request ID',
            'created_at' => 'Created At',
            'is_deleted' => 'is Deleted',
            'amount' => 'Amount',
            'fk_pr_allotment_id' => 'Pr Allotment ID',
        ];
    }

    /**
     * Gets query for [[FkPrPurchaseRequest]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkPrPurchaseRequest()
    {
        return $this->hasOne(PrPurchaseRequest::class, ['id' => 'fk_pr_purchase_request_id']);
    }

    /**
     * Gets query for [[FkTransaction]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkTransaction()
    {
        return $this->hasOne(Transaction::class, ['id' => 'fk_transaction_id']);
    }
}
