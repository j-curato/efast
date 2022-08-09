<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pr_office".
 *
 * @property int $id
 * @property string|null $office
 * @property string|null $division
 * @property string|null $unit
 * @property string $created_at
 */
class PrOffice extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pr_office';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at'], 'safe'],
            [['office', 'division', 'unit'], 'string', 'max' => 255],
            [['fk_unit_head'], 'integer',],
            [[
                'id',
                'office',
                'division',
                'unit',
                'responsibility_code',
                'created_at',

            ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'office' => 'Office',
            'division' => 'Division',
            'unit' => 'Unit',
            'created_at' => 'Created At',
            'fk_unit_head' => 'Unit Head',
        ];
    }
    public function getUnitHead()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'fk_unit_head']);
    }
}
