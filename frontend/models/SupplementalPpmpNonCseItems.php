<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%supplemental_ppmp_non_cse_items}}".
 *
 * @property int $id
 * @property int|null $fk_supplemental_ppmp_non_cse_id
 * @property float $amount
 * @property int|null $fk_pr_stock_id
 * @property int|null $is_deleted
 * @property string $deleted_at
 * @property string $created_at
 */
class SupplementalPpmpNonCseItems extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%supplemental_ppmp_non_cse_items}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_supplemental_ppmp_non_cse_id', 'fk_pr_stock_id', 'is_deleted'], 'integer'],
            [['amount', 'quantity'], 'required'],
            [['amount'], 'number'],
            [['description'], 'string'],
            [['deleted_at', 'created_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_supplemental_ppmp_non_cse_id' => 'Fk Supplemental Ppmp Non Cse ID',
            'amount' => 'Amount',
            'quantity' => 'Quantity',
            'description' => 'Description',
            'fk_pr_stock_id' => 'Fk Pr Stock ID',
            'is_deleted' => 'Is Deleted',
            'deleted_at' => 'Deleted At',
            'created_at' => 'Created At',
        ];
    }
}
