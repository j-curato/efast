<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bac_composition".
 *
 * @property int $id
 * @property string|null $effectivity_date
 * @property string|null $expiration_date
 * @property string|null $rso_number
 *
 * @property BacCompositionMember[] $bacCompositionMembers
 */
class BacComposition extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bac_composition';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['effectivity_date', 'expiration_date'], 'safe'],
            [['effectivity_date', 'expiration_date'], 'required'],
            [['rso_number'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'effectivity_date' => 'Effectivity Date',
            'expiration_date' => 'Expiration Date',
            'rso_number' => 'Rso Number',
        ];
    }

    /**
     * Gets query for [[BacCompositionMembers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBacCompositionMembers()
    {
        return $this->hasMany(BacCompositionMember::class, ['bac_composition_id' => 'id']);
    }
}
