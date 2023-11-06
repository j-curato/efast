<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bank_branches".
 *
 * @property int $id
 * @property int $fk_bank_id
 * @property string $branch_name
 * @property string $created_at
 *
 * @property BankBranchDetails[] $bankBranchDetails
 * @property Banks $fkBank
 */
class BankBranches extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bank_branches';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_bank_id', 'branch_name'], 'required'],
            [['fk_bank_id'], 'integer'],
            [['branch_name'], 'string'],
            [['created_at'], 'safe'],
            [['fk_bank_id'], 'exist', 'skipOnError' => true, 'targetClass' => Banks::class, 'targetAttribute' => ['fk_bank_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_bank_id' => 'Bank',
            'branch_name' => 'Branch Name',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[BankBranchDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBankBranchDetails()
    {
        return $this->hasMany(BankBranchDetails::class, ['fk_bank_branch_id' => 'id']);
    }

    /**
     * Gets query for [[FkBank]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBank()
    {
        return $this->hasOne(Banks::class, ['id' => 'fk_bank_id']);
    }
}
