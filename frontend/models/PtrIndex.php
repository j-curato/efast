<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ptr_index".
 *
 * @property int $id
 * @property string $ptr_number
 * @property string|null $date
 * @property string|null $office_name
 * @property string|null $property_number
 * @property string|null $description
 * @property string|null $receive_by
 * @property string|null $article
 * @property string|null $par_number
 */
class PtrIndex extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ptr_index';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'ptr_number'], 'required'],
            [['id'], 'integer'],
            [['date'], 'safe'],
            [['description', 'receive_by', 'article'], 'string'],
            [['ptr_number', 'office_name', 'property_number', 'par_number'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ptr_number' => 'Ptr Number',
            'date' => 'Date',
            'office_name' => 'Office Name',
            'property_number' => 'Property Number',
            'description' => 'Description',
            'receive_by' => 'Receive By',
            'article' => 'Article',
            'par_number' => 'Par Number',
        ];
    }
}
