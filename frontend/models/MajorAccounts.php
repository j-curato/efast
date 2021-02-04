<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "major_accounts".
 *
 * @property int $id
 * @property string $name
 * @property string $object_code
 *
 * @property ChartOfAccounts[] $chartOfAccounts
 */
class MajorAccounts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'major_accounts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'object_code'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['object_code'], 'string', 'max' => 20],
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
            'object_code' => 'Object Code',
        ];
    }

    /**
     * Gets query for [[ChartOfAccounts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getChartOfAccounts()
    {
        return $this->hasMany(ChartOfAccounts::className(), ['major_account_id' => 'id']);
    }
}
