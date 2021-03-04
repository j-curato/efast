<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cash_flow".
 *
 * @property int $id
 * @property string|null $major_cashflow
 * @property string|null $sub_cashflow1
 * @property string|null $sub_cashflow2
 * @property string|null $specific_cashflow
 */
class CashFlow extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cash_flow';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['major_cashflow', 'sub_cashflow1', 'sub_cashflow2', 'specific_cashflow'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'major_cashflow' => 'Major Cashflow',
            'sub_cashflow1' => 'Sub Cashflow1',
            'sub_cashflow2' => 'Sub Cashflow2',
            'specific_cashflow' => 'Specific Cashflow',
        ];
    }
}
