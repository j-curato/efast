<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "report_type".
 *
 * @property int $id
 * @property string|null $name
 */
class ReportType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'report_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name','advance_type'], 'string', 'max' => 255],
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
}
