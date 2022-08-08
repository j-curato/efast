<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%request_for_inspection_items}}".
 *
 * @property int $id
 * @property int|null $fk_request_for_inspection_id
 * @property int|null $fk_pr_purchase_order_items_aoq_item_id
 * @property string $created_at
 *
 * @property RequestForInspection $fkRequestForInspection
 */
class RequestForInspectionItems extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%request_for_inspection_items}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_request_for_inspection_id', 'fk_pr_purchase_order_items_aoq_item_id'], 'integer'],
            [[
                'created_at',
                'from',
                'to'
            ], 'safe'],
            [['fk_request_for_inspection_id'], 'exist', 'skipOnError' => true, 'targetClass' => RequestForInspection::class, 'targetAttribute' => ['fk_request_for_inspection_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_request_for_inspection_id' => 'Fk Request For Inspection ID',
            'fk_pr_purchase_order_items_aoq_item_id' => 'Fk Purchase Order ID',
            'created_at' => 'Created At',
            'from' => 'From',
            'to' => 'To'
        ];
    }

    /**
     * Gets query for [[FkRequestForInspection]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRequestForInspection()
    {
        return $this->hasOne(RequestForInspection::class, ['id' => 'fk_request_for_inspection_id']);
    }
}
