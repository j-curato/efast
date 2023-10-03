<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pr_aoq_entries".
 *
 * @property int $id
 * @property int|null $pr_aoq_id
 * @property int|null $payee_id
 * @property float|null $amount
 * @property string|null $remark
 * @property int|null $is_lowest
 */
class PrAoqEntries extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pr_aoq_entries';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pr_aoq_id', 'payee_id', 'is_lowest', 'pr_rfq_item_id', 'is_deleted'], 'integer'],
            [['amount'], 'number'],
            [['remark'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pr_aoq_id' => 'Pr Aoq ID',
            'payee_id' => 'Payee ID',
            'amount' => 'Amount',
            'remark' => 'Remark',
            'is_lowest' => 'Is Lowest',
            'pr_rfq_item_id' => 'RFQ Item',
            'is_deleted' => 'is Deleted',

        ];
    }
    public function getPayee()
    {

        return $this->hasOne(Payee::class, ['id' => 'payee_id']);
    }
    public function getPrRfqItem()
    {
        return $this->hasOne(PrRfqItem::class, ['id' => 'pr_rfq_item_id']);
    }
}
