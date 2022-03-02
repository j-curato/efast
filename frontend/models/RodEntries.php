<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%rod_entries}}".
 *
 * @property int $id
 * @property string|null $rod_number
 * @property int|null $advances_entries_id
 *
 * @property Rod $rodNumber
 */
class RodEntries extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%rod_entries}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['advances_entries_id'], 'integer'],
            [['rod_number'], 'string', 'max' => 255],
            [['rod_number'], 'exist', 'skipOnError' => true, 'targetClass' => Rod::className(), 'targetAttribute' => ['rod_number' => 'rod_number']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'rod_number' => 'Rod Number',
            'advances_entries_id' => 'Advances Entries ID',
        ];
    }

    /**
     * Gets query for [[RodNumber]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRodNumber()
    {
        return $this->hasOne(Rod::class, ['rod_number' => 'rod_number']);
    }
    public function getAdvancesEntries()
    {

        return $this->hasOne(AdvancesEntries::class, ['id' => 'advances_entries_id']);
    }
}
