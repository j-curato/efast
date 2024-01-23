<?php

namespace app\models;

use Yii;
use app\behaviors\HistoryLogsBehavior;

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
    public function behaviors()
    {
        return [
            HistoryLogsBehavior::class
        ];
    }
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
            [['id', 'fk_remittance_id', 'fk_dv_acounting_entries_id', 'is_removed'], 'integer'],
            [['created_at'], 'safe'],
            [['id'], 'unique'],

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
            'is_removed' => 'is Removed',
            'fk_dv_acounting_entries_id' => 'Fk Dv Acounting Entries ID',
            'created_at' => 'Created At',
        ];
    }
}
