<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "property_details".
 *
 * @property int $id
 * @property string|null $property_number
 * @property string $created_at
 */
class PropertyDetails extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'property_details';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at'], 'safe'],
            [['property_number'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'property_number' => 'Property Number',
            'created_at' => 'Created At',
        ];
    }
}
