<?php

namespace app\models;

use Yii;
use app\behaviors\HistoryLogsBehavior;

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

    public function behaviors()
    {
        return [
            'historyLogs' => [
                'class' => HistoryLogsBehavior::class,
            ],
        ];
    }
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
            [['created_at', 'date_generated'], 'safe'],
            [['iar_number'], 'string', 'max' => 255],
            [['iar_number'], 'unique'],
            [['id'], 'unique'],
        ];
    }

    public function getIarDetails()
    {
        return Yii::$app->db->createCommand("SELECT * FROM iar_index WHERE id =:id")->bindValue(':id', $this->id)->queryOne();
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
    $currentYear = date('Y');
    $currentMonth = date('m');

    $query = Yii::$app->db->createCommand("
        SELECT CAST(SUBSTRING_INDEX(iar_number,'-',-1) AS UNSIGNED) as last_num
        FROM iar 
        WHERE iar_number LIKE :office
        ORDER BY last_num DESC LIMIT 1
    ")
    ->bindValue(':office', $this->office_name . '%')
    ->queryScalar();

    $lastNumYear = substr($query, 3, 4);
    $resetSeries = ($currentYear !== $lastNumYear || ($currentYear === $lastNumYear && $currentMonth === '01'));

    $nextNumber = $resetSeries ? 1 : intval($query) + 1;
    
    return strtoupper($this->office_name) . '-' . date('Y-m') . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
}

/*     private function generateSerialNum()
    {
        // Get the current year and month
        $currentYear = date('Y');
        $currentMonth = date('m');
    
        // Check if the month is January and if the previous year's last IAR exists
        if ($currentMonth === '01') {
            $lastYear = $currentYear - 1;
            $lastIAR = Yii::$app->db->createCommand("
                SELECT MAX(SUBSTRING_INDEX(iar_number, '-', -1)) AS last_num
                FROM iar
                WHERE iar_number LIKE :office
            ")
            ->bindValue(':office', strtoupper($this->office_name) . "-$lastYear-__")
            ->queryScalar();
    
            $nextNumber = $lastIAR !== null ? intval($lastIAR) + 1 : 1;
        } else {
            // Get the next series number for the current year and month
            $currentIAR = Yii::$app->db->createCommand("
                SELECT MAX(SUBSTRING_INDEX(iar_number, '-', -1)) AS last_num
                FROM iar
                WHERE iar_number LIKE :office
            ")
            ->bindValue(':office', strtoupper($this->office_name) . "-$currentYear-$currentMonth-%")
            ->queryScalar();
    
            $nextNumber = $currentIAR !== null ? intval($currentIAR) + 1 : 1;
        }
    
        // Construct the new serial number
        $serialNumber = strtoupper($this->office_name) . '-' . date('Y-m') . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    
        return $serialNumber;
    }
    
/*     private function generateSerialNum()
    {
        $query = Yii::$app->db->createCommand("SELECT CAST(SUBSTRING_INDEX(iar_number,'-',-1) AS UNSIGNED) as last_num
         FROM iar 
         WHERE 
         iar_number LIKE :office
         ORDER BY last_num DESC LIMIT 1")
            ->bindValue(':office', $this->office_name . '%')
            ->queryScalar();
        $nxtNum = !empty($query) ? intval($query) + 1 : 1;
        return strtoupper($this->office_name) . '-' . date('Y') . '-' . str_pad($nxtNum, 4, '0', STR_PAD_LEFT);
    } */
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
            'date_generated' => 'Date Generated'
        ];
    }
    public function getInspectionReport()
    {
        return $this->hasOne(InspectionReport::class, ['id' => 'fk_ir_id']);
    }
}
