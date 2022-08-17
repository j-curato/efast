<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%transaction_iars}}".
 *
 * @property int $id
 * @property int|null $fk_transaction_id
 * @property int|null $fk_iar_id
 */
class TransactionIars extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%transaction_iars}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_transaction_id', 'fk_iar_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_transaction_id' => 'Fk Transaction ID',
            'fk_iar_id' => 'Fk Iar ID',
        ];
    }
}
