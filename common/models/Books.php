<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%books}}".
 *
 * @property int $id
 * @property string $name
 * @property string|null $account_number
 *
 * @property CashDisbursement[] $cashDisbursements
 * @property CashRecieved[] $cashRecieveds
 */
class Books extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%books}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'type'], 'required'],
            [['name', 'account_number', 'type'], 'string', 'max' => 255],
            [[
                'id',
                'name',
                'account_number',
                'type',

            ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
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
            'account_number' => 'Account Number',
            'type' => 'Book Type',
        ];
    }

    /**
     * Gets query for [[CashDisbursements]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\CashDisbursementQuery
     */
    public function getCashDisbursements()
    {
        return $this->hasMany(CashDisbursement::className(), ['book_id' => 'id']);
    }

    /**
     * Gets query for [[CashRecieveds]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\CashRecievedQuery
     */
    public function getCashRecieveds()
    {
        return $this->hasMany(CashRecieved::className(), ['book_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\BooksQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\BooksQuery(get_called_class());
    }
}
