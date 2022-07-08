<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "property_card".
 *
 * @property string|null $pc_number
 * @property float|null $balance
 * @property string|null $par_number
 */
class PropertyCard extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'property_card';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['balance', 'fk_par_id'], 'number'],
            [['pc_number', 'par_number'], 'string', 'max' => 255],
            [[
                'pc_number',
                'balance',
                'par_number',
                'ptr_number',
                'id',
                'fk_par_id',
                'created_at',

            ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pc_number' => 'Pc Number',
            'balance' => 'Balance',
            'par_number' => 'Par Number',
        ];
    }
    public function getPar()
    {
        return $this->hasOne(Par::class, ['par_number' => 'par_number']);
    }
}
