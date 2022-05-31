<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "monthly_liquidation_program".
 *
 * @property int $id
 * @property string|null $reporting_period
 * @property float|null $amount
 * @property int|null $book_id
 * @property string|null $province
 * @property string|null $fund_source_type
 * @property string $created_at
 */
class MonthlyLiquidationProgram extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'monthly_liquidation_program';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['amount'], 'number'],
            [['book_id'], 'integer'],
            [['created_at'], 'safe'],
            [['reporting_period', 'province'], 'string', 'max' => 20],
            [['fund_source_type'], 'string', 'max' => 255],
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
            'amount' => 'Amount',
            'book_id' => 'Book ID',
            'province' => 'Province',
            'fund_source_type' => 'Fund Source Type',
            'created_at' => 'Created At',
        ];
    }
    public function getBook()
    {

        return $this->hasOne(Books::class, ['id' => 'book_id']);
    }
}
