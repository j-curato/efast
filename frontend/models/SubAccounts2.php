<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sub_accounts2".
 *
 * @property int $id
 * @property int $sub_accounts1_id
 * @property string $object_code
 * @property string $name
 *
 * @property SubAccounts1 $subAccounts1
 */
class SubAccounts2 extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sub_accounts2';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sub_accounts1_id', 'object_code', 'name'], 'required'],
            [['sub_accounts1_id', 'is_active'], 'integer'],
            [['object_code', 'name'], 'string', 'max' => 255],
            [[
                'id',
                'sub_accounts1_id',
                'object_code',
                'name',
                'is_active',

            ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
            [['sub_accounts1_id'], 'exist', 'skipOnError' => true, 'targetClass' => SubAccounts1::class, 'targetAttribute' => ['sub_accounts1_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sub_accounts1_id' => 'Sub Accounts1 ID',
            'object_code' => 'Object Code',
            'name' => 'Account Title',
            'is_active' => 'Active'
        ];
    }

    /**
     * Gets query for [[SubAccounts1]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubAccounts1()
    {
        return $this->hasOne(SubAccounts1::class, ['id' => 'sub_accounts1_id']);
    }
}
