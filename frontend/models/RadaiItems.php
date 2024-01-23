<?php

namespace app\models;

use Yii;
use app\behaviors\HistoryLogsBehavior;

/**
 * This is the model class for table "radai_items".
 *
 * @property int $id
 * @property int|null $fk_radai_id
 * @property int|null $fk_lddap_ada_id
 * @property int|null $is_deleted
 *
 * @property LddapAdas $fkLddapAda
 * @property Radai $fkRadai
 */
class RadaiItems extends \yii\db\ActiveRecord
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
        return 'radai_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_radai_id', 'fk_lddap_ada_id', 'is_deleted'], 'integer'],
            [['fk_lddap_ada_id'], 'exist', 'skipOnError' => true, 'targetClass' => LddapAdas::className(), 'targetAttribute' => ['fk_lddap_ada_id' => 'id']],
            [['fk_radai_id'], 'exist', 'skipOnError' => true, 'targetClass' => Radai::className(), 'targetAttribute' => ['fk_radai_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_radai_id' => 'Fk Radai ID',
            'fk_lddap_ada_id' => 'Fk Lddap Ada ID',
            'is_deleted' => 'Is Deleted',
        ];
    }

    /**
     * Gets query for [[FkLddapAda]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkLddapAda()
    {
        return $this->hasOne(LddapAdas::className(), ['id' => 'fk_lddap_ada_id']);
    }

    /**
     * Gets query for [[FkRadai]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkRadai()
    {
        return $this->hasOne(Radai::className(), ['id' => 'fk_radai_id']);
    }
}
