<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vw_not_in_coa_transmittal".
 *
 * @property int $id
 * @property string $transmittal_number
 */
class VwNotInCoaTransmittal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vw_not_in_coa_transmittal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'transmittal_number'], 'required'],
            [['id'], 'integer'],
            [['transmittal_number'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'transmittal_number' => 'Transmittal Number',
        ];
    }
}
