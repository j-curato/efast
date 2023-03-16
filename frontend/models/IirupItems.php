<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "iirup_items".
 *
 * @property int $id
 * @property int $fk_iirup_id
 * @property int $fk_par_id
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
            [['fk_iirup_id', 'fk_par_id'], 'required'],
            [['fk_iirup_id', 'fk_par_id', 'is_deleted'], 'integer'],
            [['created_at'], 'safe'],
            [['fk_iirup_id'], 'exist', 'skipOnError' => true, 'targetClass' => Iirup::className(), 'targetAttribute' => ['fk_iirup_id' => 'id']],
            [['fk_par_id'], 'exist', 'skipOnError' => true, 'targetClass' => Par::className(), 'targetAttribute' => ['fk_par_id' => 'id']],
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
            'fk_par_id' => 'Fk Par ID',
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
        return $this->hasOne(Iirup::className(), ['id' => 'fk_iirup_id']);
    }

    /**
     * Gets query for [[FkPar]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkPar()
    {
        return $this->hasOne(Par::className(), ['id' => 'fk_par_id']);
    }
}
