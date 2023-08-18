<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%inspection_report}}".
 *
 * @property int $id
 * @property string $ir_number
 * @property string $created_at
 */
class InspectionReport extends \yii\db\ActiveRecord
{
    public $office_name;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%inspection_report}}';
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [

            [['id', 'fk_end_user'], 'integer'],
            [['created_at'], 'safe'],
            [['ir_number', 'office_name'], 'string', 'max' => 255],
            [['ir_number'], 'unique'],
            [['id'], 'unique'],
        ];
    }
    public function beforeSave($insert)
    {


        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {

                if (empty($this->ir_number)) {
                    $this->ir_number = $this->generateSerialNum();
                }
                if (empty($this->id)) {
                    $this->id = Yii::$app->db->createCommand("SELECT UUID_SHORT() % 9223372036854775807")->queryScalar();
                }
            }
            return true;
        }
        return false;
    }
    private function generateSerialNum()
    {
        $query = Yii::$app->db->createCommand("SELECT CAST(SUBSTRING_INDEX(ir_number,'-',-1) AS UNSIGNED) as last_num
         FROM inspection_report 
         WHERE 
         ir_number LIKE :office
         ORDER BY last_num DESC LIMIT 1")
            ->bindValue(':office', $this->office_name . '%')
            ->queryScalar();
        $nxtNum = !empty($query) ? intval($query) + 1 : 1;
        return strtoupper($this->office_name) . '-' . date('Y') . '-' . str_pad($nxtNum, 4, '0', STR_PAD_LEFT);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ir_number' => 'Ir Number',
            'fk_end_user' => 'End-User',
            'created_at' => 'Created At',
        ];
    }
    public function getInspectionReportItems()
    {
        return $this->hasMany(InspectionReportItems::class, ['fk_inspection_report_id' => 'id']);
    }
}
