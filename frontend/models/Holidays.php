<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "holidays".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $date
 */
class Holidays extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'holidays';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'date'], 'string', 'max' => 255],
            [[
                'id',
                'name',
                'date',
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
            'date' => 'Date',
        ];
    }
}
