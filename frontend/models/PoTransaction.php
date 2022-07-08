<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "po_transaction".
 *
 * @property int $id
 * @property int|null $po_responsibility_center_id
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
            [['po_responsibility_center_id'], 'integer'],
            [['po_responsibility_center_id', 'amount', 'particular', 'reporting_period'], 'required'],
            [['payee', 'particular'], 'string'],
            [['amount'], 'number'],
            [['payroll_number', 'tracking_number', 'reporting_period'], 'string', 'max' => 100],
            [[
                'id',
                'payee',
                'particular',
                'amount',
                'payroll_number',
                'tracking_number',
                'po_responsibility_center_id',
                'province',
                'created_at',
                'reporting_period',

            ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
            [['po_responsibility_center_id'], 'exist', 'skipOnError' => true, 'targetClass' => PoResponsibilityCenter::class, 'targetAttribute' => ['po_responsibility_center_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'po_responsibility_center_id' => 'Responsibility Center ID',
            'payee' => 'Payee',
            'particular' => 'Particular',
            'amount' => 'Gross Amount',
            'payroll_number' => 'Payroll Number',
            'tracking_number' => 'Tracking Number',
            'reporting_period' => 'Reporting Period',
        ];
    }

    /**
     * Gets query for [[ResponsibilityCenter]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPoResponsibilityCenter()
    {
        return $this->hasOne(PoResponsibilityCenter::class, ['id' => 'po_responsibility_center_id']);
    }
}
