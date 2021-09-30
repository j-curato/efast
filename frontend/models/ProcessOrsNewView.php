<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "process_ors_new_view".
 *
 * @property int|null $id
 * @property string|null $serial_number
 * @property string|null $tracking_number
 * @property string|null $payee
 * @property string|null $particular
 * @property string|null $allotment_uacs
 * @property string|null $allotment_account_title
 * @property string|null $ors_uacs
 * @property string|null $ors_account_tiitle
 * @property float|null $amount
 * @property int|null $is_cancelled
 */
class ProcessOrsNewView extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'process_ors_new_view';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'is_cancelled'], 'integer'],
            [['amount'], 'number'],
            [['serial_number', 'tracking_number', 'payee', 'particular', 'allotment_account_title', 'ors_account_title'], 'string', 'max' => 255],
            [['allotment_uacs', 'ors_uacs'], 'string', 'max' => 30],
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
            'tracking_number' => 'Tracking Number',
            'payee' => 'Payee',
            'particular' => 'Particular',
            'allotment_uacs' => 'Allotment Uacs',
            'allotment_account_title' => 'Allotment Account Title',
            'ors_uacs' => 'Ors Uacs',
            'ors_account_title' => 'Ors Account Tiitle',
            'amount' => 'Amount',
            'is_cancelled' => 'Is Cancelled',
            'ors_book' => ' ORS Book',
            'allotment_book' => 'Allotment Book',
            'mfo_name' => 'MFO/PAP Name',
            'document_name' => 'Document Recieve',
        ];
    }
}
