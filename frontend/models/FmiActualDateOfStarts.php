<?php

namespace app\models;

use Yii;
use yii\db\Expression;
use app\behaviors\GenerateIdBehavior;
use app\behaviors\HistoryLogsBehavior;

/**
 * This is the model class for table "tbl_fmi_actual_date_of_starts".
 *
 * @property int $id
 * @property string $serial_number
 * @property int $fk_tbl_fmi_subproject_id
 * @property int|null $fk_office_id
 * @property string $actual_date_of_start
 * @property string $created_at
 *
 * @property Office $fkOffice
 * @property FmiSubprojects $fkTblFmiSubproject
 */
class FmiActualDateOfStarts extends \yii\db\ActiveRecord
{

    public function behaviors()
    {
        return [
            GenerateIdBehavior::class,
            HistoryLogsBehavior::class
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_fmi_actual_date_of_starts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_tbl_fmi_subproject_id', 'actual_date_of_start'], 'required'],
            [['id', 'fk_tbl_fmi_subproject_id', 'fk_office_id'], 'integer'],
            [['actual_date_of_start', 'created_at'], 'safe'],
            [['serial_number'], 'string', 'max' => 255],
            [['serial_number'], 'unique'],
            [['id'], 'unique'],
            [['fk_office_id'], 'exist', 'skipOnError' => true, 'targetClass' => Office::class, 'targetAttribute' => ['fk_office_id' => 'id']],
            [['fk_tbl_fmi_subproject_id'], 'exist', 'skipOnError' => true, 'targetClass' => FmiSubprojects::class, 'targetAttribute' => ['fk_tbl_fmi_subproject_id' => 'id']],
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
            'fk_tbl_fmi_subproject_id' => ' Subproject',
            'fk_office_id' => ' Office',
            'actual_date_of_start' => 'Actual Date Of Start',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[FkOffice]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOffice()
    {
        return $this->hasOne(Office::class, ['id' => 'fk_office_id']);
    }

    /**
     * Gets query for [[FkTblFmiSubproject]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFmiSubproject()
    {
        return $this->hasOne(FmiSubprojects::class, ['id' => 'fk_tbl_fmi_subproject_id']);
    }
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                if (empty($this->serial_number)) {
                    $this->serial_number = $this->generateSerialNumber();
                }
            }
            return true;
        }
        return false;
    }
    private  function generateSerialNumber()
    {
        $lastNum = $this->querySerialLastNumber();
        $num = !empty($lastNum) ? floatval($lastNum) + 1 : 1;
        return strtoupper($this->office->office_name) . '-' . date('Y') . '-' . str_pad($num, 5, '0', STR_PAD_LEFT);
    }
    private function querySerialLastNumber()
    {

        return self::find()
            ->addSelect([
                new Expression("CAST(SUBSTRING_INDEX(serial_number,'-',-1) AS UNSIGNED) as last_num")
            ])
            ->andWhere(['fk_office_id' => $this->fk_office_id])
            ->orderBy(['last_num' => SORT_DESC])
            ->limit(1)
            ->scalar();
    }
}
