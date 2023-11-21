<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_mg_liquidation_items".
 *
 * @property int $id
 * @property int|null $fk_mg_liquidation_id
 * @property int|null $fk_notification_to_pay_id
 * @property int|null $is_deleted
 * @property string|null $created_at
 *
 * @property MgLiquidations $fkMgLiquidation
 * @property NotificationToPay $fkNotificationToPay
 */
class MgLiquidationItems extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_mg_liquidation_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_mg_liquidation_id', 'fk_notification_to_pay_id', 'date'], 'required'],
            [['fk_mg_liquidation_id', 'fk_notification_to_pay_id', 'is_deleted'], 'integer'],
            [['created_at', 'date'], 'safe'],
            [['fk_mg_liquidation_id'], 'exist', 'skipOnError' => true, 'targetClass' => MgLiquidations::class, 'targetAttribute' => ['fk_mg_liquidation_id' => 'id']],
            [['fk_notification_to_pay_id'], 'exist', 'skipOnError' => true, 'targetClass' => NotificationToPay::class, 'targetAttribute' => ['fk_notification_to_pay_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_mg_liquidation_id' => 'Fk Mg Liquidation ID',
            'fk_notification_to_pay_id' => 'Fk Notification To Pay ID',
            'is_deleted' => 'Is Deleted',
            'created_at' => 'Created At',
            'date' => 'Date',

        ];
    }

    /**
     * Gets query for [[FkMgLiquidation]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMgLiquidation()
    {
        return $this->hasOne(MgLiquidations::class, ['id' => 'fk_mg_liquidation_id']);
    }

    /**
     * Gets query for [[FkNotificationToPay]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNotificationToPay()
    {
        return $this->hasOne(NotificationToPay::class, ['id' => 'fk_notification_to_pay_id']);
    }
}
