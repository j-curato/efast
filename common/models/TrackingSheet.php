<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%tracking_sheet}}".
 *
 * @property int $id
 * @property int|null $payee_id
 * @property int|null $process_ors_id
 * @property string|null $tracking_number
 * @property string|null $particular
 * @property string|null $transaction_type
 * @property float|null $gross_amount
 * @property string $created_at
 *
 * @property Payee $payee
 * @property ProcessOrs $processOrs
 */
class TrackingSheet extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tracking_sheet}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['payee_id', 'process_ors_id'], 'integer'],
            [['particular'], 'string'],
            [['gross_amount'], 'number'],
            [['created_at'], 'safe'],
            [['tracking_number', 'transaction_type'], 'string', 'max' => 255],
            [[
                'id',
                'payee_id',
                'process_ors_id',
                'tracking_number',
                'particular',
                'transaction_type',
                'gross_amount',
                'created_at',
            ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
            [['payee_id'], 'exist', 'skipOnError' => true, 'targetClass' => Payee::class, 'targetAttribute' => ['payee_id' => 'id']],
            [['process_ors_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProcessOrs::class, 'targetAttribute' => ['process_ors_id' => 'id']],
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
            'process_ors_id' => 'Process Ors ID',
            'tracking_number' => 'Tracking Number',
            'particular' => 'Particular',
            'transaction_type' => 'Transaction Type',
            'gross_amount' => 'Gross Amount',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Payee]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\PayeeQuery
     */
    public function getPayee()
    {
        return $this->hasOne(Payee::class, ['id' => 'payee_id']);
    }

    /**
     * Gets query for [[ProcessOrs]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\ProcessOrsQuery
     */
    public function getProcessOrs()
    {
        return $this->hasOne(ProcessOrs::class, ['id' => 'process_ors_id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\TrackingSheetQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\TrackingSheetQuery(get_called_class());
    }
}
