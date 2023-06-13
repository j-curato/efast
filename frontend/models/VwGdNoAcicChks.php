<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vw_gd_no_acic_chks".
 *
 * @property int $id
 * @property string|null $check_or_ada_no
 * @property string|null $ada_number
 * @property string|null $issuance_date
 * @property string|null $book_name
 */
class VwGdNoAcicChks extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vw_gd_no_acic_chks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['check_or_ada_no'], 'string', 'max' => 100],
            [['ada_number'], 'string', 'max' => 40],
            [['issuance_date'], 'string', 'max' => 50],
            [['book_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'check_or_ada_no' => 'Check Or Ada No',
            'ada_number' => 'Ada Number',
            'issuance_date' => 'Issuance Date',
            'book_name' => 'Book Name',
            'reporting_period' => 'Reporting Period',
            'ttl' => 'Total',
        ];
    }
}
