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
            [['effectivity_date', 'expiration_date', 'fk_office_id'], 'required'],
            [['fk_office_id', 'is_disabled'], 'integer'],
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
            'rso_number' => 'RSO No.',
            'created_at' => 'Created At',
            'fk_office_id' => 'Office ',
            'is_disabled' => 'Disabled ',

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
    public static function getBacMembersByDate($date, $fk_office_id)
    {
        return Yii::$app->db->createCommand("SELECT 
                employee_search_view.employee_id,
                UPPER(employee_search_view.employee_name) as employee_name,
                CONCAT(bac_position.position,'_', employee_search_view.employee_name) as pos,
                LOWER(bac_position.position)as position
                FROM bac_composition
                LEFT JOIN bac_composition_member ON bac_composition.id  = bac_composition_member.bac_composition_id
                LEFT JOIN bac_position ON bac_composition_member.bac_position_id = bac_position.id
                LEFT JOIN employee_search_view ON bac_composition_member.employee_id = employee_search_view.employee_id
                WHERE 
                :_date  >= bac_composition.effectivity_date 
                AND :_date<= bac_composition.expiration_date
                AND fk_office_id = :office_id
                AND is_disabled = 0")
            ->bindValue(':_date', $date)
            ->bindValue(':office_id',   $fk_office_id)
            ->queryAll();
    }
}
