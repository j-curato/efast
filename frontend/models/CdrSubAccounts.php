<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cdr_sub_accounts".
 *
 * @property int $id
 * @property string|null $object_code
 * @property string|null $name
 * @property string|null $province
 */
class CdrSubAccounts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cdr_sub_accounts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'string'],
            [['object_code'], 'string', 'max' => 255],
            [['province'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'object_code' => 'Object Code',
            'name' => 'Name',
            'province' => 'Province',
        ];
    }
}
