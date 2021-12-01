<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%transaction}}".
 *
 * @property int $id
 * @property int|null $responsibility_center_id
 * @property int $payee_id
 * @property string $particular
 * @property float $gross_amount
 * @property string|null $tracking_number
 * @property string|null $earmark_no
 * @property string|null $payroll_number
 * @property string|null $transaction_date
 * @property string|null $transaction_time
 * @property string $created_at
 *
 * @property ProcessBurs[] $processBurs
 * @property ProcessOrs[] $processOrs
 * @property Payee $payee
 * @property ResponsibilityCenter $responsibilityCenter
 */
class Transaction extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%transaction}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['responsibility_center_id', 'payee_id'], 'integer'],
            [['payee_id', 'particular', 'gross_amount'], 'required'],
            [['gross_amount'], 'number'],
            [['created_at'], 'safe'],
            [['particular', 'tracking_number', 'earmark_no', 'payroll_number'], 'string', 'max' => 255],
            [['transaction_date'], 'string', 'max' => 50],
            [['transaction_time'], 'string', 'max' => 20],
            [['payee_id'], 'exist', 'skipOnError' => true, 'targetClass' => Payee::className(), 'targetAttribute' => ['payee_id' => 'id']],
            [['responsibility_center_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResponsibilityCenter::className(), 'targetAttribute' => ['responsibility_center_id' => 'id']],
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
            'payee_id' => 'Payee ID',
            'particular' => 'Particular',
            'gross_amount' => 'Gross Amount',
            'tracking_number' => 'Tracking Number',
            'earmark_no' => 'Earmark No',
            'payroll_number' => 'Payroll Number',
            'transaction_date' => 'Transaction Date',
            'transaction_time' => 'Transaction Time',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[ProcessBurs]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\ProcessBursQuery
     */
    public function getProcessBurs()
    {
        return $this->hasMany(ProcessBurs::className(), ['transaction_id' => 'id']);
    }

    /**
     * Gets query for [[ProcessOrs]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\ProcessOrsQuery
     */
    public function getProcessOrs()
    {
        return $this->hasMany(ProcessOrs::className(), ['transaction_id' => 'id']);
    }

    /**
     * Gets query for [[Payee]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\PayeeQuery
     */
    public function getPayee()
    {
        return $this->hasOne(Payee::className(), ['id' => 'payee_id']);
    }

    /**
     * Gets query for [[ResponsibilityCenter]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\ResponsibilityCenterQuery
     */
    public function getResponsibilityCenter()
    {
        return $this->hasOne(ResponsibilityCenter::className(), ['id' => 'responsibility_center_id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\TransactionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\TransactionQuery(get_called_class());
    }
}
