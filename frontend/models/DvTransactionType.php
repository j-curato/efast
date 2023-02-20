<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%dv_transaction_type}}".
 *
 * @property int $id
 * @property string $name
 * @property string $create_at
 */
class DvTransactionType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%dv_transaction_type}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['create_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
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
            'create_at' => 'Create At',
        ];
    }
}
