<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pr_contract_type".
 *
 * @property int $id
 * @property string|null $contract_name
 */
class PrContractType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pr_contract_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['contract_name'], 'string', 'max' => 255],
            [[

                'id',
                'contract_name',

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
            'contract_name' => 'Contract Name',
        ];
    }
}
