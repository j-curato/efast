<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "transmittal".
 *
 * @property int $id
 * @property int|null $cash_disbursement_id
 * @property string|null $transmittal_number
 * @property string|null $location
 *
 * @property CashDisbursement $cashDisbursement
 */
class Transmittal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transmittal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cash_disbursement_id'], 'integer'],
            [['transmittal_number'], 'string', 'max' => 100],
            [['location'], 'string', 'max' => 20],
            [['cash_disbursement_id'], 'exist', 'skipOnError' => true, 'targetClass' => CashDisbursement::className(), 'targetAttribute' => ['cash_disbursement_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cash_disbursement_id' => 'Cash Disbursement ID',
            'transmittal_number' => 'Transmittal Number',
            'location' => 'Location',
        ];
    }

    /**
     * Gets query for [[CashDisbursement]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCashDisbursement()
    {
        return $this->hasOne(CashDisbursement::className(), ['id' => 'cash_disbursement_id']);
    }
}
