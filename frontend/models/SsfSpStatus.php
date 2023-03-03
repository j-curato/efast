<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ssf_sp_status".
 *
 * @property int $id
 * @property string|null $name
 *
 * @property SsfSpNum[] $ssfSpNums
 */
class SsfSpStatus extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ssf_sp_status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
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
        ];
    }

    /**
     * Gets query for [[SsfSpNums]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSsfSpNums()
    {
        return $this->hasMany(SsfSpNum::class, ['fk_ssf_sp_status_id' => 'id']);
    }
}
