<?php

namespace app\models;

use Yii;
use app\behaviors\HistoryLogsBehavior;

/**
 * This is the model class for table "mode_of_payments".
 *
 * @property int $id
 * @property string $name
 * @property string $created_at
 */
class ModeOfPayments extends \yii\db\ActiveRecord
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
        return 'mode_of_payments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name','check_type'], 'required'],
            [['check_type'], 'integer'],
            [['created_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
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
            'check_type' => 'Check Type',
            'created_at' => 'Created At',

        ];
    }
}
