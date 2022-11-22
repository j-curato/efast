<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%trip_ticket}}".
 *
 * @property int $id
 * @property string $date
 * @property int $driver
 * @property string $serial_no
 * @property string $purpose
 * @property int $authorized_by
 * @property string $created_at
 *
 * @property TripTicketItems[] $tripTicketItems
 */
class TripTicket extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%trip_ticket}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'date', 'driver', 'serial_no', 'purpose', 'authorized_by', 'car_id'], 'required'],
            [['id', 'driver', 'authorized_by', 'car_id'], 'integer'],
            [['date', 'created_at', 'to_date'], 'safe'],
            [['purpose'], 'string'],
            [['serial_no',], 'string', 'max' => 255],
            [['serial_no'], 'unique'],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Date',
            'to_date' => 'To Date',
            'driver' => 'Driver',
            'serial_no' => 'Serial No',
            'purpose' => 'Purpose',
            'authorized_by' => 'Authorized By',
            'created_at' => 'Created At',
            'car_id' => 'Car Type',
        ];
    }

    /**
     * Gets query for [[TripTicketItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTripTicketItems()
    {
        return $this->hasMany(TripTicketItems::class, ['fk_trip_ticket_id' => 'id']);
    }
    public function getCarDriver()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'driver']);
    }
    public function getCarType()
    {
        return $this->hasOne(Cars::class, ['id' => 'car_id']);
    }
}
