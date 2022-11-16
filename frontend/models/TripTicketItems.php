<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%trip_ticket_items}}".
 *
 * @property int $id
 * @property int $fk_trip_ticket_id
 * @property string|null $departure_time
 * @property string|null $departure_place
 * @property string|null $arrival_time
 * @property string|null $arrival_place
 * @property int|null $passenger_id
 *
 * @property TripTicket $fkTripTicket
 */
class TripTicketItems extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%trip_ticket_items}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_trip_ticket_id'], 'required'],
            [['fk_trip_ticket_id', 'passenger_id'], 'integer'],
            [['departure_place', 'arrival_place'], 'string'],
            [['departure_time', 'arrival_time'], 'string', 'max' => 255],
            [['fk_trip_ticket_id'], 'exist', 'skipOnError' => true, 'targetClass' => TripTicket::className(), 'targetAttribute' => ['fk_trip_ticket_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_trip_ticket_id' => 'Fk Trip Ticket ID',
            'departure_time' => 'Departure Time',
            'departure_place' => 'Departure Place',
            'arrival_time' => 'Arrival Time',
            'arrival_place' => 'Arrival Place',
            'passenger_id' => 'Passenger ID',
        ];
    }

    /**
     * Gets query for [[FkTripTicket]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkTripTicket()
    {
        return $this->hasOne(TripTicket::className(), ['id' => 'fk_trip_ticket_id']);
    }
}
