<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "po_transmittals_pending".
 *
 * @property string $transmittal_number
 * @property string|null $date
 * @property string $created_at
 * @property string|null $status
 * @property int|null $edited
 * @property float|null $total_withdrawals
 */
class PoTransmittalsPending extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'po_transmittals_pending';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['transmittal_number'], 'required'],
            [['date', 'created_at'], 'safe'],
            [['edited'], 'integer'],
            [['total_withdrawals'], 'number'],
            [['transmittal_number', 'status'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'transmittal_number' => 'Transmittal Number',
            'date' => 'Date',
            'created_at' => 'Created At',
            'status' => 'Status',
            'edited' => 'Edited',
            'total_withdrawals' => 'Total Withdrawals',
        ];
    }
}
