<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "iirup_index".
 *
 * @property int $id
 * @property string $serial_number
 * @property string|null $office_name
 * @property string|null $approved_by
 * @property string|null $accountable_officer
 */
class IirupIndex extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'iirup_index';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'serial_number'], 'required'],
            [['id'], 'integer'],
            [['approved_by', 'accountable_officer'], 'string'],
            [['serial_number', 'office_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'serial_number' => 'Serial Number',
            'office_name' => 'Office Name',
            'approved_by' => 'Approved By',
            'accountable_officer' => 'Accountable Officer',
        ];
    }
}
