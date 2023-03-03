<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%supervisor_validation_notes}}".
 *
 * @property int $id
 * @property string $employee_name
 * @property string $evaluation_period
 * @property string $ttl_suc_msr
 * @property string $valid_msr
 * @property string $accomplishments
 * @property string $pgs_rating
 * @property string $comment
 * @property int $passion
 * @property int $integrety
 * @property int $competence
 * @property int $creativity
 * @property int $synergy
 * @property int $love_of_country
 * @property int $int_gbl_olk
 * @property int $del_solution
 * @property int $net_link
 * @property int $del_exl_res
 * @property int $collaborating
 * @property int $agility
 * @property int $proflsm_int
 * @property string $dev_intervention
 * @property string $created_at
 */
class SupervisorValidationNotes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%supervisor_validation_notes}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'employee_name', 'evaluation_period', 'ttl_suc_msr', 'valid_msr', 'accomplishments', 'pgs_rating', 'comment', 'passion', 'integrety', 'competence', 'creativity', 'synergy', 'love_of_country', 'int_gbl_olk', 'del_solution', 'net_link', 'del_exl_res', 'collaborating', 'agility', 'proflsm_int', 'dev_intervention'], 'required'],
            [['id', 'passion', 'integrety', 'competence', 'creativity', 'synergy', 'love_of_country', 'int_gbl_olk', 'del_solution', 'net_link', 'del_exl_res', 'collaborating', 'agility', 'proflsm_int'], 'integer'],
            [['employee_name', 'comment', 'dev_intervention'], 'string'],
            [['created_at'], 'safe'],
            [['evaluation_period', 'ttl_suc_msr', 'valid_msr', 'accomplishments', 'pgs_rating'], 'string', 'max' => 255],
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
            'employee_name' => '1. Employee Name (Last Name, First Name, M.I)',
            'evaluation_period' => '2. Evaluation Period',
            'ttl_suc_msr' => '3. Total no. of success measures',
            'valid_msr' => '4. Valid measures for evaluation period',
            'accomplishments' => '5. Measures with 90% accomplishment for the evaluation period',
            'pgs_rating' => '6. Tentative PGS Rating',
            'comment' => '7. Comment from the supervisor',
            'passion' => 'Passion',
            'integrety' => 'Integrety',
            'competence' => 'Competence',
            'creativity' => 'Creativity',
            'synergy' => 'Synergy',
            'love_of_country' => 'Love of Country',

            'int_gbl_olk' => 'Integrated Industry and Globalized Outlook',
            'del_solution' => "Delivering Solutions, Services and Support to DTI's Stakeholders",
            'net_link' => 'Networking and Linkaging',
            'del_exl_res' => 'Delivering Exellent Results',
            'collaborating' => 'Collaborating',
            'agility' => 'Agility',
            'proflsm_int' => 'Exemplifying Professionalism and Integrity',
            'dev_intervention' => '10. Staff learning and Development Intervention needed',
            'created_at' => 'Created At',
        ];
    }
}
