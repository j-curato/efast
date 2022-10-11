<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%other_property_detail_items}}".
 *
 * @property int $id
 * @property int|null $fk_other_property_details_id
 * @property int $book_id
 * @property float $amount
 * @property string $created_at
 *
 * @property OtherPropertyDetails $fkOtherPropertyDetails
 */
class OtherPropertyDetailItems extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%other_property_detail_items}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_other_property_details_id', 'book_id', 'is_deleted'], 'integer'],
            [['book_id', 'amount'], 'required'],
            [['amount'], 'number'],
            [['created_at'], 'safe'],

            [[
                'book_id',
                'amount',
            ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
            [['fk_other_property_details_id'], 'exist', 'skipOnError' => true, 'targetClass' => OtherPropertyDetails::class, 'targetAttribute' => ['fk_other_property_details_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_other_property_details_id' => 'Fk Other Property Details ID',
            'book_id' => 'Book ID',
            'amount' => 'Amount',
            'is_deleted' => 'is_deleted',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[FkOtherPropertyDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkOtherPropertyDetails()
    {
        return $this->hasOne(OtherPropertyDetails::class, ['id' => 'fk_other_property_details_id']);
    }
}
