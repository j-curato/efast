<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_fmi_bank_deposit_types".
 *
 * @property int $id
 * @property string|null $deposit_type
 * @property string $created_at
 *
 * @property FmiBankDeposits[] $tblFmiBankDeposits
 */
class FmiBankDepositTypes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_fmi_bank_deposit_types';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at'], 'safe'],
            [['deposit_type'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'deposit_type' => 'Deposit Type',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[FmiBankDeposits]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFmiBankDeposits()
    {
        return $this->hasMany(FmiBankDeposits::class, ['fk_fmi_bank_deposit_type_id' => 'id']);
    }

    public static function getAllTypesA()
    {
        return self::find()->asArray()->all();
    }
}
