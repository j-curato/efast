<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "po_responsibility_center".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $description
 */
class PoResponsibilityCenter extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'po_responsibility_center';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['description', 'name', 'province'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['province'], 'string', 'max' => 20],
            [[
                'id',
                'name',
                'description',
                'province',
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
            'province' => 'Province',
        ];
    }
}
