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

    public function getIrDetails()
    {
        return Yii::$app->db->createCommand("SELECT * FROM inspection_report_index WHERE id = :id")->bindValue(':id', $this->id)->queryOne();
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
    public function getRfiId()
    {
        return Yii::$app->db->createCommand("SELECT 
            request_for_inspection_items.fk_request_for_inspection_id
            FROM 
            inspection_report_items
            JOIN request_for_inspection_items ON inspection_report_items.fk_request_for_inspection_item_id = request_for_inspection_items.id
            WHERE 
            fk_inspection_report_id = :id
            UNION 
            SELECT 
            rfi_without_po_items.fk_request_for_inspection_id
            FROM 
            inspection_report_no_po_items
            JOIN rfi_without_po_items ON inspection_report_no_po_items.fk_rfi_without_po_item_id = rfi_without_po_items.id
            WHERE 
            fk_inspection_report_id = :id")
            ->bindValue(':id', $this->id)
            ->queryScalar();
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
    public function getEmployee()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'fk_end_user']);
    }
}
