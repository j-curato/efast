<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%allotment_type}}".
 *
 * @property int $id
 * @property string $type
 * @property string $created_at
 */
class AllotmentType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%allotment_type}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type'], 'required'],
            [['type'], 'string'],
            [['created_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'created_at' => 'Created At',
        ];
    }
}
