<?php

namespace app\models;

use Yii;
use app\behaviors\HistoryLogsBehavior;

/**
 * This is the model class for table "mfo_pap_code".
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $description
 */
class MfoPapCode extends \yii\db\ActiveRecord
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
        return 'mfo_pap_code';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'name', 'description', 'division'], 'required'],
            [['code', 'name', 'description', 'division'], 'string', 'max' => 255],
            [[
                'description',
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
            'code' => 'Code',
            'name' => 'Name',
            'description' => 'Description',
            'division' => 'Division',
        ];
    }
    public static function getMfoPapCodesA()
    {
        return MfoPapCode::find()->asArray()->all();
    }
}
