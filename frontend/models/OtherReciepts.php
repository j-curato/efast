<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "other_reciepts".
 *
 * @property int $id
 * @property string|null $report
 * @property string|null $province
 * @property string|null $fund_source
 * @property string|null $advance_type
 * @property string|null $object_code
 */
class OtherReciepts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'other_reciepts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fund_source'], 'string'],
            [['report', 'province'], 'string', 'max' => 50],
            [['advance_type'], 'string', 'max' => 100],
            [['object_code'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'report' => 'Report',
            'province' => 'Province',
            'fund_source' => 'Fund Source',
            'advance_type' => 'Advance Type',
            'object_code' => 'Object Code',
        ];
    }
}
