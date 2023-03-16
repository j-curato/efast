<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "iirup_items".
 *
 * @property int $id
 * @property int $fk_iirup_id
 * @property int $fk_other_property_detail_item_id
 * @property int|null $is_deleted
 * @property string $created_at
 *
 * @property Iirup $fkIirup
 * @property Par $fkPar
 */
class IirupItems extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'iirup_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_iirup_id', 'fk_other_property_detail_item_id'], 'required'],
            [['fk_iirup_id', 'fk_other_property_detail_item_id', 'is_deleted'], 'integer'],
            [['created_at'], 'safe'],
            [['fk_iirup_id'], 'exist', 'skipOnError' => true, 'targetClass' => Iirup::class, 'targetAttribute' => ['fk_iirup_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_iirup_id' => 'Fk Iirup ID',
            'fk_other_property_detail_item_id' => 'Fk Par ID',
            'is_deleted' => 'Is Deleted',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[FkIirup]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkIirup()
    {
        return $this->hasOne(Iirup::class, ['id' => 'fk_iirup_id']);
    }
}
