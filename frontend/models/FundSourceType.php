<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "fund_source_type".
 *
 * @property int $id
 * @property string|null $name
 */
class FundSourceType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fund_source_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 255],
            [['division'], 'string', 'max' => 50],
            [['division','name'], 'required', ],
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
            'division' => 'Division',
        ];
    }
    public function getAdvancesEntries()
    {
        return $this->hasMany(AdvancesEntries::class, ['fund_source_type' => 'name']);
    }
}
