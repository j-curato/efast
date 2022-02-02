<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tracking_sheet_index".
 *
 * @property int $id
 * @property string|null $particular
 * @property string|null $dv_number
 * @property string|null $account_name
 */
class TrackingSheetIndex extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tracking_sheet_index';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['particular'], 'string'],
            [['dv_number', 'account_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'particular' => 'Particular',
            'dv_number' => 'Dv Number',
            'account_name' => 'Account Name',
        ];
    }
}
