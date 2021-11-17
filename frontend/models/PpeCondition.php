<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ppe_condition".
 *
 * @property int $id
 * @property string|null $condition
 */
class PpeCondition extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ppe_condition';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['condition'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'condition' => 'Condition',
        ];
    }
}
