<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "authorization_code".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 */
class AuthorizationCode extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'authorization_code';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'description'], 'required'],
            [['name', 'description'], 'string', 'max' => 255],
            [[
                'id',
                'name',
                'description',
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
            'description' => 'Description',
        ];
    }
}
