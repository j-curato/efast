<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ptr".
 *
 * @property string|null $ptr_number
 * @property string|null $par_number
 * @property int|null $transfer_type
 * @property string|null $date
 * @property string|null $reason
 * @property string|null $from
 * @property string|null $to
 */
class Ptr extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ptr';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['transfer_type'], 'integer'],
            [['date'], 'safe'],
            [['reason'], 'string'],
            [['ptr_number', 'par_number', 'from', 'to'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ptr_number' => 'Ptr Number',
            'par_number' => 'Par Number',
            'transfer_type' => 'Transfer Type',
            'date' => 'Date',
            'reason' => 'Reason',
            'from' => 'From',
            'to' => 'To',
        ];
    }
}
