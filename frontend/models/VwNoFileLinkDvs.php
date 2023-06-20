<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vw_no_file_link_dvs".
 *
 * @property int $id
 * @property string|null $dv_number
 */
class VwNoFileLinkDvs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vw_no_file_link_dvs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['dv_number'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dv_number' => 'Dv Number',
        ];
    }
}
