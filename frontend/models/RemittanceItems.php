<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "remittance_items".
 *
 * @property int $id
 * @property int|null $fk_remittance_id
 * @property int|null $fk_dv_acounting_entries_id
 * @property string $created_at
 */
class RemittanceItems extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'remittance_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'fk_remittance_id', 'fk_dv_acounting_entries_id'], 'integer'],
            [['created_at'], 'safe'],
            [['id'], 'unique'],
            [[
                'id',
                'fk_remittance_id',
                'fk_dv_acounting_entries_id',
                'amount',
                'is_removed',
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
            'id' => 'ID',
            'fk_remittance_id' => 'Fk Remittance ID',
            'fk_dv_acounting_entries_id' => 'Fk Dv Acounting Entries ID',
            'created_at' => 'Created At',
        ];
    }
}
