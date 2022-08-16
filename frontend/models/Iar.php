<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%iar}}".
 *
 * @property int $id
 * @property string $iar_number
 * @property string $created_at
 */
class Iar extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%iar}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'iar_number'], 'required'],
            [['id', 'fk_ir_id', 'fk_end_user'], 'integer'],
            [['created_at'], 'safe'],
            [['iar_number'], 'string', 'max' => 255],
            [['iar_number'], 'unique'],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'iar_number' => 'Iar Number',
            'created_at' => 'Created At',
            'fk_ir_id' => 'IR ID',
            'fk_end_user' => 'End User',
        ];
    }
    public function getInspetionReport()
    {
        return $this->hasOne(InspectionReport::class, ['id' => 'fk_ir_id']);
    }
}
