<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vw_po_transmittal_index".
 *
 * @property int $id
 * @property string $transmittal_number
 * @property string|null $date
 * @property int|null $is_accepted
 * @property string|null $status
 * @property int|null $fk_office_id
 */
class VwPoTransmittalIndex extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vw_po_transmittal_index';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'transmittal_number'], 'required'],
            [['id', 'is_accepted', 'fk_office_id'], 'integer'],
            [['date'], 'safe'],
            [['transmittal_number'], 'string', 'max' => 255],
            [['status'], 'string', 'max' => 13],
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
            'date' => 'Date',
            'is_accepted' => 'Is Accepted',
            'status' => 'Status',
            'fk_office_id' => 'Fk Office ID',
        ];
    }
}
