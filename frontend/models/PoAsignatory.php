<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "po_asignatory".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $position
 */
class PoAsignatory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'po_asignatory';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'position','province'], 'string', 'max' => 255],
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
            'position' => 'Position',
            'province'=>'Province'
        ];
    }
}
