<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%ppmp_non_cse_item_categories}}".
 *
 * @property int $id
 * @property int|null $ppmp_non_cse_item_id
 * @property int $fk_stock_type
 * @property float|null $budget
 * @property string $created_at
 *
 * @property PpmpNonCseItems $ppmpNonCseItem
 */
class PpmpNonCseItemCategories extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%ppmp_non_cse_item_categories}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ppmp_non_cse_item_id', 'fk_stock_type','is_deleted'], 'integer'],
            [['fk_stock_type'], 'required'],
            [['budget'], 'number'],
            [['created_at'], 'safe'],
            [['ppmp_non_cse_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => PpmpNonCseItems::class, 'targetAttribute' => ['ppmp_non_cse_item_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ppmp_non_cse_item_id' => 'Ppmp Non Cse Item ID',
            'fk_stock_type' => 'Fk Stock Type',
            'budget' => 'Budget',
            'created_at' => 'Created At',
            'is_deleted' => 'Deleted',
        ];
    }

    /**
     * Gets query for [[PpmpNonCseItem]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPpmpNonCseItem()
    {
        return $this->hasOne(PpmpNonCseItems::class, ['id' => 'ppmp_non_cse_item_id']);
    }
}
