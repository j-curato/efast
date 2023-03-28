<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "property_database".
 *
 * @property string|null $pc_num
 * @property string|null $ptr_number
 * @property string|null $ptr_date
 * @property string|null $type
 * @property string|null $derecognition_num
 * @property string|null $derecognition_date
 * @property string|null $property_number
 * @property string|null $date_acquired
 * @property string|null $serial_number
 * @property string|null $article
 * @property string|null $description
 * @property float|null $acquisition_amount
 * @property string|null $unit_of_measure
 * @property string|null $strt_mnth
 * @property string|null $lst_mth
 * @property string|null $new_last_month
 * @property string|null $sec_lst_mth
 * @property float|null $salvage_value
 * @property float|null $depreciable_amount
 * @property float|null $mnthly_depreciation
 * @property float|null $lstmnthdep
 * @property string|null $par_number
 * @property string|null $par_date
 * @property string|null $rcv_by
 * @property string|null $act_usr
 * @property string|null $isd_by
 * @property string|null $office_name
 * @property string|null $division
 * @property string|null $location
 * @property string|null $isCrntUsr
 * @property string|null $isUnserviceable
 * @property string|null $uacs
 * @property string|null $general_ledger
 * @property string|null $depreciation_account_title
 * @property string|null $depreciation_object_code
 * @property string|null $book_name
 * @property string|null $iirup_num
 */
class PropertyDatabase extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'property_database';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ptr_date', 'derecognition_date', 'date_acquired'], 'safe'],
            [['article', 'description', 'rcv_by', 'act_usr', 'isd_by'], 'string'],
            [['acquisition_amount', 'salvage_value', 'depreciable_amount', 'mnthly_depreciation', 'lstmnthdep'], 'number'],
            [['pc_num', 'ptr_number', 'type', 'derecognition_num', 'property_number', 'serial_number', 'unit_of_measure', 'par_number', 'par_date', 'office_name', 'division', 'location', 'general_ledger', 'depreciation_account_title', 'depreciation_object_code', 'book_name', 'iirup_num'], 'string', 'max' => 255],
            [['strt_mnth', 'lst_mth', 'new_last_month', 'sec_lst_mth'], 'string', 'max' => 7],
            [['isCrntUsr'], 'string', 'max' => 16],
            [['isUnserviceable'], 'string', 'max' => 13],
            [['uacs'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pc_num' => 'Pc Num',
            'ptr_number' => 'Ptr Number',
            'ptr_date' => 'Ptr Date',
            'type' => 'Type',
            'derecognition_num' => 'Derecognition Num',
            'derecognition_date' => 'Derecognition Date',
            'property_number' => 'Property Number',
            'date_acquired' => 'Date Acquired',
            'serial_number' => 'Serial Number',
            'article' => 'Article',
            'description' => 'Description',
            'acquisition_amount' => 'Acquisition Amount',
            'unit_of_measure' => 'Unit Of Measure',
            'strt_mnth' => 'Strt Mnth',
            'lst_mth' => 'Lst Mth',
            'new_last_month' => 'New Last Month',
            'sec_lst_mth' => 'Sec Lst Mth',
            'salvage_value' => 'Salvage Value',
            'depreciable_amount' => 'Depreciable Amount',
            'mnthly_depreciation' => 'Mnthly Depreciation',
            'lstmnthdep' => 'Lstmnthdep',
            'par_number' => 'Par Number',
            'par_date' => 'Par Date',
            'rcv_by' => 'Rcv By',
            'act_usr' => 'Act Usr',
            'isd_by' => 'Isd By',
            'office_name' => 'Office Name',
            'division' => 'Division',
            'location' => 'Location',
            'isCrntUsr' => 'Is Crnt Usr',
            'isUnserviceable' => 'Is Unserviceable',
            'uacs' => 'Uacs',
            'general_ledger' => 'General Ledger',
            'depreciation_account_title' => 'Depreciation Account Title',
            'depreciation_object_code' => 'Depreciation Object Code',
            'book_name' => 'Book Name',
            'iirup_num' => 'Iirup Num',
        ];
    }
}
