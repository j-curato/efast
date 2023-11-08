<?php

namespace app\models;

use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "bank_branch_details".
 *
 * @property int $id
 * @property int $fk_bank_branch_id
 * @property string $address
 * @property string $bank_manager
 * @property int|null $is_disabled
 * @property string $created_at
 *
 * @property BankBranches $fkBankBranch
 */
class BankBranchDetails extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bank_branch_details';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_bank_branch_id', 'address', 'bank_manager'], 'required'],
            [['fk_bank_branch_id', 'is_disabled'], 'integer'],
            [['address'], 'string'],
            [['created_at'], 'safe'],
            [['bank_manager'], 'string', 'max' => 255],
            [['fk_bank_branch_id'], 'exist', 'skipOnError' => true, 'targetClass' => BankBranches::class, 'targetAttribute' => ['fk_bank_branch_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_bank_branch_id' => ' Bank Branch ID',
            'address' => 'Address',
            'bank_manager' => 'Bank Manager',
            'is_disabled' => 'Is Disabled',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[FkBankBranch]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBankBranch()
    {
        return $this->hasOne(BankBranches::class, ['id' => 'fk_bank_branch_id']);
    }
    public static function searchBankBranchDetail($text, $offset, $limit)
    {
        return BankBranchDetails::find()
            ->addSelect([
                'bank_branch_details.id',
                new Expression('UPPER(CONCAT(banks.name," - ",bank_branches.branch_name)) as text')
            ])
            ->join('JOIN', 'bank_branches', ' bank_branch_details.fk_bank_branch_id = bank_branches.id')
            ->join('JOIN', 'banks', 'bank_branches.fk_bank_id  = banks.id')
            ->andWhere(['like', 'bank_branches.branch_name', $text])
            ->andWhere('bank_branch_details.is_disabled = 0')
            ->offset($offset)
            ->limit($limit)
            ->asArray()->all();
    }
}
