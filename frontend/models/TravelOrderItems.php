<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%travel_order_items}}".
 *
 * @property int $id
 * @property int $fk_travel_order_id
 * @property int $fk_employee_id
 * @property string|null $from_date
 * @property string|null $to_date
 * @property string $created_at
 *
 * @property TravelOrder $fkTravelOrder
 */
class TravelOrderItems extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%travel_order_items}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_travel_order_id', 'fk_employee_id'], 'required'],
            [['fk_travel_order_id', 'fk_employee_id'], 'integer'],
            [['from_date', 'to_date', 'created_at'], 'safe'],
            [['fk_travel_order_id'], 'exist', 'skipOnError' => true, 'targetClass' => TravelOrder::className(), 'targetAttribute' => ['fk_travel_order_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_travel_order_id' => 'Fk Travel Order ID',
            'fk_employee_id' => 'Fk Employee ID',
            'from_date' => 'From Date',
            'to_date' => 'To Date',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[FkTravelOrder]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkTravelOrder()
    {
        return $this->hasOne(TravelOrder::className(), ['id' => 'fk_travel_order_id']);
    }
}
