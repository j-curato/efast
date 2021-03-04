<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "net_asset_equity".
 *
 * @property int $id
 * @property string $group
 * @property string $specific_change
 */
class NetAssetEquity extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'net_asset_equity';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['group', 'specific_change'], 'required'],
            [['group', 'specific_change'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'group' => 'Group',
            'specific_change' => 'Specific Change',
        ];
    }
}
