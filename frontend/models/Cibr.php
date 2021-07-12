<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cibr".
 *
 * @property int $id
 * @property string|null $reporting_period
 * @property string|null $province
 * @property string|null $book_name
 */
class Cibr extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cibr';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['reporting_period'], 'string', 'max' => 50],
            [['province', 'book_name','serial_number'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'reporting_period' => 'Reporting Period',
            'province' => 'Province',
            'serial_number' => 'Serial Number',
            'book_name' => 'Book Name',
        ];
    }
}
