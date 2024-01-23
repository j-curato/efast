<?php

namespace app\models;

use Yii;
use app\behaviors\HistoryLogsBehavior;

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
            [[
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
        return $this->hasMany(ChartOfAccounts::class, ['major_account_id' => 'id']);
    }
    public static function getMajorAccountsA()
    {
        return MajorAccounts::find()->asArray()->all();
    }
    public static function getMafMajorAccountsA()
    {
        return  MajorAccounts::find()
            ->orwhere('object_code =:object_code', ['object_code' => 5010000000])
            ->orwhere('object_code =:object_code2', ['object_code2' => 5020000000])
            ->orwhere('object_code =:object_code3', ['object_code3' => 5060000000])
            ->asArray()
            ->all();
    }
}
