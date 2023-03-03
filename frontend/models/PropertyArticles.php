<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "property_articles".
 *
 * @property int $id
 * @property string $article_name
 * @property string $create_at
 */
class PropertyArticles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'property_articles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['article_name'], 'required'],
            [['create_at'], 'safe'],
            [['article_name'], 'string', 'max' => 255],
            [['article_name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'article_name' => 'Article Name',
            'create_at' => 'Create At',
        ];
    }
}
