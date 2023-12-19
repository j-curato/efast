<?php

namespace app\models;

use Yii;
use app\behaviors\HistoryLogsBehavior;

/**
 * This is the model class for table "{{%transaction_items}}".
 *
 * @property int $id
 * @property int $fk_transaction_id
 * @property int $fk_record_allotment_entries_id
 * @property float $amount
 * @property string $created_at
 */
class TransactionItems extends \yii\db\ActiveRecord
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
        return '{{%transaction_items}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_transaction_id', 'fk_record_allotment_entries_id', 'amount'], 'required'],
            [['fk_transaction_id', 'fk_record_allotment_entries_id', 'is_deleted'], 'integer'],
            [['amount'], 'number'],
            [['created_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_transaction_id' => 'Fk Transaction ID',
            'fk_record_allotment_entries_id' => 'Fk Record Allotment Entries ID',
            'amount' => 'Amount',
            'created_at' => 'Created At',
            'is_deleted' => 'is Deleted',
        ];
    }
}
