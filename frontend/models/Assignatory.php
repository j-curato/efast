<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "assignatory".
 *
 * @property int $id
 * @property string|null $name
 */
class Assignatory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'assignatory';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'position'], 'string', 'max' => 255],
            [[
                'id',
                'name',
                'position'
            ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
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
            'position' => 'Position',
        ];
    }
}
