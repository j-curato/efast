<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%divisions}}".
 *
 * @property int $id
 * @property string $division
 * @property int $fk_division_chief
 * @property string $created_at
 */
class Divisions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%divisions}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'division', 'fk_division_chief'], 'required'],
            [['id', 'fk_division_chief'], 'integer'],
            [['created_at'], 'safe'],
            [['division'], 'string', 'max' => 255],
            [['division'], 'unique'],
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
            'division' => 'Division',
            'fk_division_chief' => 'Fk Division Chief',
            'created_at' => 'Created At',
        ];
    }
    public function getEmployee()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'fk_division_chief']);
    }
}
