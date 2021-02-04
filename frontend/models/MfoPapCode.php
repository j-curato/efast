<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mfo_pap_code".
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $description
 */
class MfoPapCode extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mfo_pap_code';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'name', 'description'], 'required'],
            [['code', 'name', 'description'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'name' => 'Name',
            'description' => 'Description',
        ];
    }
}
