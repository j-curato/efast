<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%purchase_order_for_transmittal}}".
 *
 * @property int $id
 * @property string $serial_number
 * @property string|null $payee
 */
class PurchaseOrderForTransmittal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%purchase_order_for_transmittal}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'serial_number'], 'required'],
            [['id'], 'integer'],
            [['serial_number', 'payee'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'serial_number' => 'PO Number',
            'payee' => 'Payee',
            'purpose' => 'Purpose',
        ];
    }
}
