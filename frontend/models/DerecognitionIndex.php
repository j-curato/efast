<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "derecognition_index".
 *
 * @property int $id
 * @property string $serial_number
 * @property string $derecognition_date
 * @property string|null $property_number
 * @property string|null $article
 * @property string|null $description
 */
class DerecognitionIndex extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'derecognition_index';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'serial_number', 'derecognition_date'], 'required'],
            [['id'], 'integer'],
            [['derecognition_date'], 'safe'],
            [['article', 'description'], 'string'],
            [['serial_number', 'property_number'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'serial_number' => 'Serial Number',
            'derecognition_date' => 'Derecognition Date',
            'property_number' => 'Property Number',
            'article' => 'Article',
            'description' => 'Description',
        ];
    }
}
