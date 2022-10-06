<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%ppe_useful_life}}".
 *
 * @property int $id
 * @property string $name
 * @property int|null $life_from
 * @property int|null $life_to
 * @property string|null $life_description
 * @property string $type
 * @property string $created_at
 */
class PpeUsefulLife extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%ppe_useful_life}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'life_description'], 'string'],
            [['life_from', 'life_to'], 'integer'],
            [['created_at'], 'safe'],
            [['type'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'life_from' => 'Life From',
            'life_to' => 'Life To',
            'life_description' => 'Life Description',
            'type' => 'Type',
            'created_at' => 'Created At',
        ];
    }
}
