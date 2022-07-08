<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pr_iar_item".
 *
 * @property int $id
 * @property int $fk_pr_iar_id
 * @property int|null $quantity
 * @property int|null $fk_pr_aoq_entry_id
 */
class PrIarItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pr_iar_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_pr_iar_id'], 'required'],
            [['fk_pr_iar_id', 'quantity', 'fk_pr_aoq_entry_id'], 'integer'],
            [[
                'id',
                'fk_pr_iar_id',
                'quantity',
                'fk_pr_aoq_entry_id',
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
            'fk_pr_iar_id' => 'Fk Pr Iar ID',
            'quantity' => 'Quantity',
            'fk_pr_aoq_entry_id' => 'Fk Pr Aoq Entry ID',
        ];
    }
}
