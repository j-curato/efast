<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "po_transaction".
 *
 * @property int $id
 * @property int|null $responsibility_center_id
 * @property string|null $payee
 * @property string|null $particular
 * @property float|null $amount
 * @property string|null $payroll_number
 *
 * @property ResponsibilityCenter $responsibilityCenter
 */
class PoTransaction extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'po_transaction';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['responsibility_center_id'], 'integer'],
            [['responsibility_center_id', 'amount', 'particular'], 'required'],
            [['payee', 'particular'], 'string'],
            [['amount'], 'number'],
            [['payroll_number', 'tracking_number'], 'string', 'max' => 100],
            [['responsibility_center_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResponsibilityCenter::class, 'targetAttribute' => ['responsibility_center_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'responsibility_center_id' => 'Responsibility Center ID',
            'payee' => 'Payee',
            'particular' => 'Particular',
            'amount' => 'Amount',
            'payroll_number' => 'Payroll Number',
            'tracking_number' => 'Tracking Number',
        ];
    }

    /**
     * Gets query for [[ResponsibilityCenter]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResponsibilityCenter()
    {
        return $this->hasOne(ResponsibilityCenter::class, ['id' => 'responsibility_center_id']);
    }
}
