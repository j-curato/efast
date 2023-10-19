<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "uacs_object_codes".
 *
 * @property string $object_code
 */
class UacsObjectCodes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'uacs_object_codes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['object_code'], 'required'],
            [['object_code'], 'string', 'max' => 255],
            [['object_code'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'object_code' => 'Object Code',
        ];
    }
}
