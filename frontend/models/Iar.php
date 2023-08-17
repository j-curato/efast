<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%iar}}".
 *
 * @property int $id
 * @property string $iar_number
 * @property string $created_at
 */
class Iar extends \yii\db\ActiveRecord
{
    public $office_name;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%iar}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'fk_ir_id', 'fk_end_user'], 'integer'],
            [['created_at'], 'safe'],
            [['iar_number'], 'string', 'max' => 255],
            [['iar_number'], 'unique'],
            [['id'], 'unique'],
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                if (empty($this->id)) {
                    $this->id = Yii::$app->db->createCommand("SELECT UUID_SHORT() % 9223372036854775807")->queryScalar();
                }
                if (empty($this->iar_number)) {
                    $this->iar_number = $this->generateSerialNum();
                }
            }
            return true;
        }
        return false;
    }
    private function generateSerialNum()
    {

        $num = 1;
        $query = Yii::$app->db->createCommand("SELECT CAST(SUBSTRING_INDEX(iar_number,'-',-1) AS UNSIGNED) as last_num
         FROM iar 
         WHERE 
         iar_number LIKE :office
         ORDER BY last_num DESC LIMIT 1")
            ->bindValue(':office', $this->office_name . '%')
            ->queryScalar();
        if (!empty($query)) {
            $num = intval($query) + 1;
        }
        $zero = '';
        for ($i = strlen($num); $i <= 4; $i++) {
            $zero .= 0;
        }
        return strtoupper($this->office_name) . '-' . date('Y') . '-' . $zero . $num;
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'iar_number' => 'Iar Number',
            'created_at' => 'Created At',
            'fk_ir_id' => 'IR ID',
            'fk_end_user' => 'End User',
        ];
    }
    public function getInspectionReport()
    {
        return $this->hasOne(InspectionReport::class, ['id' => 'fk_ir_id']);
    }
}
