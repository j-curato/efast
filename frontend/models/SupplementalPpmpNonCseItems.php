<?php

namespace app\models;

use Yii;
use app\behaviors\HistoryLogsBehavior;

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
        return '{{%supplemental_ppmp_non_cse_items}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_supplemental_ppmp_non_cse_id', 'fk_pr_stock_id', 'is_deleted'], 'integer'],
            [['amount', 'quantity', 'fk_pr_stock_id', 'fk_unit_of_measure_id'], 'required'],
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
            'fk_unit_of_measure_id' => 'Unit of Measure',
        ];
    }
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            if ($this->isNewRecord) {
                if (empty($this->id)) {
                    $this->id = Yii::$app->db->createCommand("SELECT UUID_SHORT() % 9223372036854775807")->queryScalar();
                }
            }
            return true;
        }
        return false;
    }
}
