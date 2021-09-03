<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "fur".
 *
 * @property int $id
 * @property string|null $reporting_period
 * @property string|null $province
 * @property string $created_at
 */
class Fur extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fur';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at'], 'safe'],
            [['reporting_period'], 'string', 'max' => 50],
            [['province'], 'string', 'max' => 20],
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
            'created_at' => 'Created At',
        ];
    }
}
