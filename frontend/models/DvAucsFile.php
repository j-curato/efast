<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%dv_aucs_file}}".
 *
 * @property int $id
 * @property int|null $fk_dv_aucs_id
 * @property string|null $file_name
 * @property string $created_at
 *
 * @property DvAucs $fkDvAucs
 */
class DvAucsFile extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%dv_aucs_file}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_dv_aucs_id'], 'integer'],
            [['file_name'], 'string'],
            [['created_at'], 'safe'],
            [['fk_dv_aucs_id'], 'exist', 'skipOnError' => true, 'targetClass' => DvAucs::className(), 'targetAttribute' => ['fk_dv_aucs_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_dv_aucs_id' => 'Fk Dv Aucs ID',
            'file_name' => 'File Name',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[FkDvAucs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkDvAucs()
    {
        return $this->hasOne(DvAucs::className(), ['id' => 'fk_dv_aucs_id']);
    }
}
