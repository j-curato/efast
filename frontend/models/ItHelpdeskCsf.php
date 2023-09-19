<?php

namespace app\models;

use DateTime;
use Yii;

/**
 * This is the model class for table "it_helpdesk_csf".
 *
 * @property int $id
 * @property string $serial_number
 * @property int $fk_it_maintenance_request

 * @property string $date
 * @property int $clarity
 * @property int $skills
 * @property int $professionalism
 * @property int $courtesy
 * @property int $response_time
 * @property string|null $comment
 * @property string|null $vd_reason
 * @property string $created_at
 *
 * @property ItMaintenanceRequest $fkClient
 * @property ItMaintenanceRequest $fkItMaintenanceRequest
 */
class ItHelpdeskCsf extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'it_helpdesk_csf';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_it_maintenance_request',  'date', 'clarity', 'skills', 'professionalism', 'courtesy', 'response_time', 'outcome'], 'required'],
            [['fk_it_maintenance_request',  'clarity', 'skills', 'professionalism', 'courtesy', 'response_time', 'outcome'], 'integer'],
            [[
                'comment', 'vd_reason', 'social_group',
            ], 'string'],
            [['date', 'created_at'], 'safe'],
            [['serial_number'], 'string', 'max' => 255],
            [['serial_number'], 'unique'],
            [['fk_it_maintenance_request'], 'exist', 'skipOnError' => true, 'targetClass' => ItMaintenanceRequest::class, 'targetAttribute' => ['fk_it_maintenance_request' => 'id']],
        ];
    }
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                if (empty($this->serial_number)) {

                    $this->serial_number = $this->generateSerialNum();
                }
            }
            return true;
        }
        return false;
    }
    private function generateSerialNum()
    {
        $dte = DateTime::createFromFormat('Y-m-d', $this->date);
        $yr = $dte->format('Y');
        $qry  = Yii::$app->db->createCommand("SELECT 
            CAST(SUBSTRING_INDEX(it_helpdesk_csf.serial_number,'-',-1)AS UNSIGNED) +1 as ser_num
            FROM it_helpdesk_csf  
            WHERE 
            it_helpdesk_csf.serial_number LIKE :yr
            ORDER BY ser_num DESC LIMIT 1")
            ->bindValue(':yr',  $yr . '%')
            ->queryScalar();
        $num =  !empty($qry) ? intval($qry) : 1;
        return  $dte->format('Y-m') . '-'  . str_pad($num, 4, '0', STR_PAD_LEFT);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'serial_number' => 'Serial Number',
            'fk_it_maintenance_request' => ' It Maintenance Request',
            'date' => 'Date',
            'clarity' => 'Clarity',
            'skills' => 'Skills',
            'professionalism' => 'Professionalism',
            'courtesy' => 'Courtesy',
            'response_time' => 'Response Time',
            'comment' => 'Comment',
            'vd_reason' => 'Vd Reason',
            'created_at' => 'Created At',
            'social_group' => 'Social Group',
            'other_social_group' => 'Other',
            'outcome' => 'OUTCOME/Result of Services Requested',
        ];
    }

    /**
     * Gets query for [[FkClient]].
     *
     * @return \yii\db\ActiveQuery
     */


    /**
     * Gets query for [[FkItMaintenanceRequest]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMaintenanceRequest()
    {
        return $this->hasOne(ItMaintenanceRequest::class, ['id' => 'fk_it_maintenance_request']);
    }
}
