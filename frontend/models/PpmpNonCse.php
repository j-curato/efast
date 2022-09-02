<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%ppmp_non_cse}}".
 *
 * @property int $id
 * @property string $project_name
 * @property string $target_month
 * @property int $fk_source_of_fund_id
 * @property int|null $fk_end_user
 * @property int $fk_pap_code_id
 * @property string $created_at
 */
class PpmpNonCse extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%ppmp_non_cse}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id'], 'integer'],
            [['created_at', 'date'], 'safe'],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',

            'date' => 'Date',
            'created_at' => 'Created At',
        ];
    }
}
