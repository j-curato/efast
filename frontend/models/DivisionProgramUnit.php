<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%division_program_unit}}".
 *
 * @property int $id
 * @property string $name
 * @property string $created_at
 */
class DivisionProgramUnit extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%division_program_unit}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'fk_mfo_pap_id'], 'required'],
            [['created_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['fk_mfo_pap_id'], 'integer'],
            [['name'], 'unique'],
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
            'created_at' => 'Created At',
            'fk_mfo_pap_id' => 'MFO/PAP ',
        ];
    }
}
