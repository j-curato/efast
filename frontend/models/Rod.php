<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rod".
 *
 * @property string $rod_number
 * @property string|null $province
 *
 * @property RodEntries[] $rodEntries
 */
class Rod extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rod';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rod_number'], 'required'],
            [['rod_number', 'province'], 'string', 'max' => 255],
            [['rod_number'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'rod_number' => 'Rod Number',
            'province' => 'Province',
        ];
    }

    /**
     * Gets query for [[RodEntries]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRodEntries()
    {
        return $this->hasMany(RodEntries::className(), ['rod_number' => 'rod_number']);
    }
}
