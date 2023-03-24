<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "derecognition".
 *
 * @property int $id
 * @property string $date
 * @property int|null $fk_iirup_id
 * @property string $last_mth_dep
 * @property string $created_at
 *
 * @property Iirup $fkIirup
 */
class Derecognition extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'derecognition';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'id',
                'date',
                'fk_property_id',
                // 'type',
                'serial_number',
            ], 'required'],
            [[
                'id',
                // 'fk_iirup_id',
                'fk_property_id',
                // 'fk_rlsddp_id',
                'type'
            ], 'integer'],
            [['created_at'], 'safe'],
            [['date', 'last_mth_dep', 'serial_number'], 'string', 'max' => 255],
            [['id'], 'unique'],
            // [['fk_iirup_id'], 'exist', 'skipOnError' => true, 'targetClass' => Iirup::class, 'targetAttribute' => ['fk_iirup_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Derecognition Date',
            // 'fk_iirup_id' => ' IIRUP No. ',
            'last_mth_dep' => 'Last Month Depreciation',
            'created_at' => 'Created At',
            'fk_property_id' => 'Property No.',
            // 'fk_rlsddp_id' => 'RLSDDP No.',
            'type' => 'Derecognition Type',
            'serial_number' => 'Serial No.',
        ];
    }

    /**
     * Gets query for [[FkIirup]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProperty()
    {
        return $this->hasOne(Property::class, ['id' => 'fk_property_id']);
    }
}
