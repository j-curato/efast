<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "property_card_index".
 *
 * @property int $id
 * @property string|null $pc_number
 * @property string|null $par_number
 * @property string|null $par_date
 * @property string|null $rcv_by
 * @property string|null $act_usr
 * @property string|null $isd_by
 * @property string|null $location
 * @property string|null $property_number
 * @property string|null $acquisition_date
 * @property float|null $acquisition_amount
 * @property string|null $description
 * @property string|null $serial_number
 * @property string|null $unit_of_measure
 * @property string|null $article
 * @property string $is_unserviceable
 * @property string|null $office_name
 */
class PropertyCardIndex extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'property_card_index';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id'], 'integer'],
            [['rcv_by', 'act_usr', 'isd_by', 'description', 'article'], 'string'],
            [['acquisition_date'], 'safe'],
            [['acquisition_amount'], 'number'],
            [['pc_number', 'par_number', 'par_date', 'location', 'property_number', 'serial_number', 'unit_of_measure', 'office_name'], 'string', 'max' => 255],
            [['is_unserviceable'], 'string', 'max' => 13],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pc_number' => 'PC Number',
            'par_number' => 'PAR Number',
            'property_number' => 'Property Number',
            'par_date' => 'Par Date',
            'rcv_by' => 'Receive By',
            'act_usr' => 'Actual User',
            'isd_by' => 'Issued By',
            'location' => 'Location',
            'acquisition_date' => 'Acquisition Date',
            'acquisition_amount' => 'Acquisition Amount',
            'description' => 'Description',
            'serial_number' => 'Serial Number',
            'unit_of_measure' => 'Unit Of Measure',
            'article' => 'Article',
            'is_unserviceable' => 'Serviceable/UnServiceable',
            'office_name' => 'Office Name',
        ];
    }
}
