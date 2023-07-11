<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vw_jev_preparation_index_view".
 *
 * @property int $id
 * @property string|null $date
 * @property string $reporting_period
 * @property string|null $entry_type
 * @property string|null $reference_type
 * @property string|null $res_center
 * @property string|null $book_name
 * @property string|null $payee
 * @property string|null $check_ada
 * @property string $explaination
 * @property string|null $dv_number
 */
class VwJevPreparationIndexView extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vw_jev_preparation_index_view';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['reporting_period', 'explaination'], 'required'],
            [['date', 'reporting_period'], 'string', 'max' => 50],
            [['entry_type', 'res_center', 'book_name', 'payee', 'check_ada', 'explaination', 'dv_number'], 'string', 'max' => 255],
            [['reference_type'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Date',
            'reporting_period' => 'Reporting Period',
            'entry_type' => 'Entry Type',
            'reference_type' => 'Reference Type',
            'res_center' => 'Responsibility Center',
            'book_name' => 'Book ',
            'payee' => 'Payee',
            'check_ada' => 'Check/ADA',
            'explaination' => 'Particular',
            'dv_number' => 'DV No.',
            'jev_number' => 'JEV No.',
        ];
    }
}
