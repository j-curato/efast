<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%cars}}".
 *
 * @property int $id
 * @property string $car_name
 * @property string $plate_number
 * @property string $created_at
 */
class Cars extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%cars}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['car_name', 'plate_number'], 'required'],
            [['created_at'], 'safe'],
            [['car_name', 'plate_number'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'car_name' => 'Car Name',
            'plate_number' => 'Plate Number',
            'created_at' => 'Created At',
        ];
    }
}
