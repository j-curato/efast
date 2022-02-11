<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ro_rao".
 *
 * @property int $id
 * @property string|null $reporting_period
 * @property string $created_at
 */
class RoRao extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ro_rao';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at'], 'safe'],
            [['reporting_period'], 'string', 'max' => 20],
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
            'created_at' => 'Created At',
        ];
    }
}
