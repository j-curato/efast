<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "document_recieve".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 */
class DocumentRecieve extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'document_recieve';
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
    public static function getDocumentReceivesA()  {
        return DocumentRecieve::find()->asArray()->all();
    }
}
