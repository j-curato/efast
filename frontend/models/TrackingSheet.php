<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tracking_sheet".
 *
 * @property int $id
 * @property int|null $payee_id
 * @property int|null $process_ors_id
 * @property string|null $tracking_number
 * @property string|null $particular
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
        return 'tracking_sheet';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['payee_id', 'process_ors_id'], 'integer'],
            [['particular'], 'string'],
            [['created_at'], 'safe'],
            [['tracking_number','transaction_type'], 'string', 'max' => 255],
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
            'created_at' => 'Created At',
            'transaction_type' => 'Transaction Type',
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
     * Gets query for [[ProcessOrs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProcessOrs()
    {
        return $this->hasOne(ProcessOrs::class, ['id' => 'process_ors_id']);
    }
    public function getDvAucs()
    {
        return $this->hasOne(DvAucs::class, [ 'tracking_sheet_id'=>'id']);
    }
}
