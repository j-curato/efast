<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "liquidation".
 *
 * @property int $id
 * @property int|null $payee_id
 * @property int|null $responsibility_center_id
 * @property string|null $check_date
 * @property string|null $check_number
 * @property string|null $dv_number
 * @property string|null $particular
 *
 * @property Payee $payee
 * @property ResponsibilityCenter $responsibilityCenter
 * @property LiquidationEntries[] $liquidationEntries
 */
class Liquidation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'liquidation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['payee_id', 'responsibility_center_id', 'po_transaction_id', 'check_range_id'], 'integer'],
            [['particular'], 'string'],
            [['check_date', 'check_number', 'province', 'cancel_reporting_period'], 'string', 'max' => 50],
            [['reporting_period'], 'string', 'max' => 20],
            [[
                'reporting_period',
                'check_date',
                'check_number',
            ], 'required'],
            [['check_range_id',], 'required', 'when' => function ($model) {
                return strtotime($model->reporting_period) > strtotime('2021-10');
            }],
            [['dv_number',], 'string', 'max' => 100],
            [['payee_id'], 'exist', 'skipOnError' => true, 'targetClass' => Payee::class, 'targetAttribute' => ['payee_id' => 'id']],
            [['responsibility_center_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResponsibilityCenter::class, 'targetAttribute' => ['responsibility_center_id' => 'id']],
            [['po_transaction_id'], 'exist', 'skipOnError' => true, 'targetClass' => PoTransaction::class, 'targetAttribute' => ['po_transaction_id' => 'id']],

        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'payee_id' => 'Payee ID',
            'responsibility_center_id' => 'Responsibility Center ',
            'check_date' => 'Check Date',
            'check_number' => 'Check Number',
            'dv_number' => 'Dv Number',
            'particular' => 'Particular',
            'reporting_period' => 'Reporting Period',
            'po_transaction_id' => 'Transaction',
            'province' => 'Province',
            'payee' => 'Payee',
            'check_range_id' => 'Check Range',
            'cancel_reporting_period' => 'Cancel Reporting Period',
        ];
    }

    /**
     * Gets query for [[Payee]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPayee()
    {
        return $this->hasOne(Payee::class, ['id' => 'payee_id']);
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
    public function getPoTransaction()
    {
        return $this->hasOne(PoTransaction::class, ['id' => 'po_transaction_id']);
    }

    /**
     * Gets query for [[LiquidationEntries]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLiquidationEntries()
    {
        return $this->hasMany(LiquidationEntries::class, ['liquidation_id' => 'id']);
    }
}
