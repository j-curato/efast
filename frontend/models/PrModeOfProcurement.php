<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pr_mode_of_procurement".
 *
 * @property int $id
 * @property string|null $mode_name
 */
class PrModeOfProcurement extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pr_mode_of_procurement';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mode_name'], 'string', 'max' => 255],
            [['description'], 'string'],
            [['is_bidding'], 'integer'],
            [['is_bidding', 'mode_name'], 'required'],
            [[

                'mode_name',

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
            'mode_name' => 'Mode Name',
            'description' => 'Description',
            'is_bidding' => 'Is BIdling',
        ];
    }

    public static function getModeOfProcurementsA()
    {
        return self::find()
            ->addSelect(['id', 'mode_name','is_bidding'])
            ->asArray()
            ->all();
    }
}
