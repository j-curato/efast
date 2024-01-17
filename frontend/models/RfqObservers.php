<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_rfq_observers".
 *
 * @property int $id
 * @property int $fk_rfq_id
 * @property string $observer_name
 * @property int|null $is_deleted
 *
 * @property PrRfq $fkRfq
 */
class RfqObservers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_rfq_observers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_rfq_id', 'observer_name'], 'required'],
            [['fk_rfq_id', 'is_deleted'], 'integer'],
            [['observer_name'], 'string', 'max' => 255],
            [['fk_rfq_id'], 'exist', 'skipOnError' => true, 'targetClass' => PrRfq::className(), 'targetAttribute' => ['fk_rfq_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_rfq_id' => 'Fk Rfq ID',
            'observer_name' => 'Observer Name',
            'is_deleted' => 'Is Deleted',
        ];
    }

    /**
     * Gets query for [[FkRfq]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkRfq()
    {
        return $this->hasOne(PrRfq::className(), ['id' => 'fk_rfq_id']);
    }
}
