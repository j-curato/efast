<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "it_helpdesk_csf".
 *
 * @property int $id
 * @property string $serial_number
 * @property int $fk_it_maintenance_request
 * @property int $fk_client_id
 * @property string|null $contact_num
 * @property string|null $address
 * @property string|null $email
 * @property string $date
 * @property int $clarity
 * @property int $skills
 * @property int $professionalism
 * @property int $courtesy
 * @property int $response_time
 * @property string $sex
 * @property string $age_group
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
            [['serial_number', 'fk_it_maintenance_request', 'fk_client_id', 'date', 'clarity', 'skills', 'professionalism', 'courtesy', 'response_time', 'sex', 'age_group'], 'required'],
            [['fk_it_maintenance_request', 'fk_client_id', 'clarity', 'skills', 'professionalism', 'courtesy', 'response_time'], 'integer'],
            [[
                'address', 'email', 'comment', 'vd_reason', 'social_group',
                'other_social_group'
            ], 'string'],
            [['date', 'created_at'], 'safe'],
            [['serial_number', 'contact_num', 'age_group'], 'string', 'max' => 255],
            [['sex'], 'string', 'max' => 20],
            [['serial_number'], 'unique'],
            [['fk_client_id'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::class, 'targetAttribute' => ['fk_client_id' => 'employee_id']],
            [['fk_it_maintenance_request'], 'exist', 'skipOnError' => true, 'targetClass' => ItMaintenanceRequest::class, 'targetAttribute' => ['fk_it_maintenance_request' => 'id']],
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
            'fk_it_maintenance_request' => 'Fk It Maintenance Request',
            'fk_client_id' => 'Fk Client ID',
            'contact_num' => 'Contact Num',
            'address' => 'Address',
            'email' => 'Email',
            'date' => 'Date',
            'clarity' => 'Clarity',
            'skills' => 'Skills',
            'professionalism' => 'Professionalism',
            'courtesy' => 'Courtesy',
            'response_time' => 'Response Time',
            'sex' => 'Sex',
            'age_group' => 'Age Group',
            'comment' => 'Comment',
            'vd_reason' => 'Vd Reason',
            'created_at' => 'Created At',
            'social_group' => 'Social Group',
            'other_social_group' => 'Other',
        ];
    }

    /**
     * Gets query for [[FkClient]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkClient()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'fk_client_id']);
    }

    /**
     * Gets query for [[FkItMaintenanceRequest]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkItMaintenanceRequest()
    {
        return $this->hasOne(ItMaintenanceRequest::class, ['id' => 'fk_it_maintenance_request']);
    }
}
