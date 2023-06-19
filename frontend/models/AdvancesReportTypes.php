<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "advances_report_type".
 *
 * @property int $id
 * @property string $name
 * @property int|null $is_deleted
 * @property string $created_at
 */
class AdvancesReportTypes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'advances_report_types';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['is_deleted'], 'integer'],
            [['created_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
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
            'is_deleted' => 'Is Deleted',
            'created_at' => 'Created At',
        ];
    }
}
