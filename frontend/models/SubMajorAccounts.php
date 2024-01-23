<?php

namespace app\models;

use Yii;
use app\behaviors\HistoryLogsBehavior;

/**
 * This is the model class for table "sub_major_accounts".
 *
 * @property int $id
 * @property string $name
 * @property string $object_code
 *
 * @property ChartOfAccounts[] $chartOfAccounts
 */
class SubMajorAccounts extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            HistoryLogsBehavior::class
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sub_major_accounts';
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
            [[
                'id',
                'name',
                'object_code',

            ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
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
        return $this->hasMany(ChartOfAccounts::class, ['sub_major_account' => 'id']);
    }
}
