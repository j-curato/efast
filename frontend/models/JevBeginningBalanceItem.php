<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "jev_beginning_balance_item".
 *
 * @property int $id
 * @property int|null $jev_beginning_balance_id
 * @property string|null $object_code
 * @property float|null $amount
 */
class JevBeginningBalanceItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'jev_beginning_balance_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['jev_beginning_balance_id'], 'integer'],
            [['debit', 'credit'], 'number'],
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
            'jev_beginning_balance_id' => 'Jev Beginning Balance ID',
            'object_code' => 'Object Code',
            'debit' => 'Debit',
            'credit' => 'Credit',
        ];
    }
}
