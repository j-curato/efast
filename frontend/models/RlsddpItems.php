<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rlsddp_items".
 *
 * @property int $id
 * @property int $fk_rlsddp_id
 * @property int $fk_par_id
 * @property string $created_at
 *
 * @property Rlsddp $fkRlsddp
 * @property Par $fkPar
 */
class RlsddpItems extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rlsddp_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_rlsddp_id', 'fk_par_id', 'is_deleted'], 'required'],
            [['fk_rlsddp_id', 'fk_par_id', 'is_deleted'], 'integer'],
            [['created_at'], 'safe'],
            [['fk_rlsddp_id'], 'exist', 'skipOnError' => true, 'targetClass' => Rlsddp::class, 'targetAttribute' => ['fk_rlsddp_id' => 'id']],
            [['fk_par_id'], 'exist', 'skipOnError' => true, 'targetClass' => Par::class, 'targetAttribute' => ['fk_par_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_rlsddp_id' => 'Fk Rlsddp ID',
            'fk_par_id' => 'Fk Par ID',
            'is_deleted' => 'Deleted',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[FkRlsddp]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkRlsddp()
    {
        return $this->hasOne(Rlsddp::class, ['id' => 'fk_rlsddp_id']);
    }

    /**
     * Gets query for [[FkPar]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkPar()
    {
        return $this->hasOne(Par::class, ['id' => 'fk_par_id']);
    }
}
