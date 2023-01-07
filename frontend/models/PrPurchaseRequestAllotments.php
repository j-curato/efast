<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%pr_purchase_request_allotments}}".
 *
 * @property int $id
 * @property int $fk_purchase_request_id
 * @property int $fk_record_allotment_entries_id
 * @property float $amount
 * @property int $is_deleted
 * @property string $created_at
 */
class PrPurchaseRequestAllotments extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%pr_purchase_request_allotments}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_purchase_request_id', 'fk_record_allotment_entries_id', 'amount'], 'required'],
            [['fk_purchase_request_id', 'fk_record_allotment_entries_id', 'is_deleted'], 'integer'],
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
            'fk_purchase_request_id' => 'Fk Purchase Request ID',
            'fk_record_allotment_entries_id' => 'Fk Record Allotment Entries ID',
            'amount' => 'Amount',
            'is_deleted' => 'Is Deleted',
            'created_at' => 'Created At',
        ];
    }
}
