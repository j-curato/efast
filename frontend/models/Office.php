<?php

namespace app\models;

use Yii;
use app\behaviors\HistoryLogsBehavior;

/**
 * This is the model class for table "{{%office}}".
 *
 * @property int $id
 * @property string $office_name
 * @property string $created_at
 */
class Office extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            HistoryLogsBehavior::class
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%office}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['office_name'], 'required'],
            [['created_at'], 'safe'],
            [['office_name'], 'string', 'max' => 255],
            [['office_name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'office_name' => 'Office Name',
            'created_at' => 'Created At',
        ];
    }
    public static function getOfficesA()
    {
        return self::find()->asArray()->all();
    }
}
