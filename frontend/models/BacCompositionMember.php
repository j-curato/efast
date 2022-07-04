<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bac_composition_member".
 *
 * @property int $id
 * @property int|null $bac_composition_id
 * @property string|null $employee_id
 * @property int|null $bac_position_id
 *
 * @property BacComposition $bacComposition
 */
class BacCompositionMember extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bac_composition_member';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bac_composition_id', 'bac_position_id'], 'integer'],
            [['employee_id'], 'string', 'max' => 255],
            [[
                'id',
                'bac_composition_id',
                'employee_id',
                'bac_position_id',
               
            ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
            [['bac_composition_id'], 'exist', 'skipOnError' => true, 'targetClass' => BacComposition::class, 'targetAttribute' => ['bac_composition_id' => 'id']],


        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'bac_composition_id' => 'Bac Composition ID',
            'employee_id' => 'Employee ID',
            'bac_position_id' => 'Bac Position ID',
            'created_at'=>'Created At',
        ];
    }

    /**
     * Gets query for [[BacComposition]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBacComposition()
    {
        return $this->hasOne(BacComposition::class, ['id' => 'bac_composition_id']);
    }
    public function getEmployee()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'employee_id']);
    }
    public function getBacPosition()
    {
        return $this->hasOne(BacPosition::class, ['id' => 'bac_position_id']);
    }
}
