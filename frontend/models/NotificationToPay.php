<?php

namespace app\models;

use Yii;
use yii\db\Expression;
use app\behaviors\GenerateIdBehavior;
use app\behaviors\HistoryLogsBehavior;

/**
 * This is the model class for table "tbl_notification_to_pay".
 *
 * @property int $id
 * @property string $serial_number
 * @property string $date
 * @property int|null $fk_due_diligence_report_id
 * @property float|null $matching_grant_amount
 * @property float|null $equity_amount
 * @property float|null $other_amount
 * @property string $created_at
 *
 * @property DueDiligenceReports $fkDueDiligenceReport
 */
class NotificationToPay extends \yii\db\ActiveRecord
{
    public $grossAmount = -1;

    public function behaviors()
    {
        return [
            'historyLogs' => [
                'class' => HistoryLogsBehavior::class,
            ],
            'generateId' => [
                'class' => GenerateIdBehavior::class,
            ],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_notification_to_pay';
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [

            [['date', 'fk_due_diligence_report_id'], 'required'],
            [[
                'id',
                'fk_due_diligence_report_id',
                'fk_office_id',
                'fk_coordinator',
                'fk_provincial_director',
            ], 'integer'],
            [['date', 'created_at'], 'safe'],
            [['matching_grant_amount', 'equity_amount', 'other_amount'], 'number'],
            [['serial_number'], 'string', 'max' => 255],
            [['serial_number'], 'unique'],
            [['id'], 'unique'],
            [['grossAmount'], 'number'],
            [['grossAmount'], 'validateTotal', 'skipOnEmpty' => false, 'skipOnError' => false],
            // [['grossAmount'], 'safe', 'on' => ['insert', 'update']],
            [['fk_due_diligence_report_id'], 'exist', 'skipOnError' => true, 'targetClass' => DueDiligenceReports::class, 'targetAttribute' => ['fk_due_diligence_report_id' => 'id']],
        ];
    }
    public function validateTotal($attribute, $params, $validator)
    {
        if ($this->grossAmount <= 0) {
            $this->addError('Gross Amount', "The sum of the amounts should be greater than zero");
        }
    }

    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            $this->grossAmount = floatval($this->matching_grant_amount) +
                floatval($this->equity_amount) +
                floatval($this->other_amount);

            return true;
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'serial_number' => 'Serial Number',
            'date' => 'Date',
            'fk_due_diligence_report_id' => ' Due Diligence Report ',
            'matching_grant_amount' => 'Matching Grant Amount',
            'equity_amount' => 'Equity Amount',
            'other_amount' => 'Other Amount',
            'created_at' => 'Created At',
            'fk_office_id' => 'Office',
            'fk_coordinator' => 'Coordinator',
            'fk_provincial_director' => 'Provincial Director',
        ];
    }

    /**
     * Gets query for [[FkDueDiligenceReport]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDueDiligenceReport()
    {
        return $this->hasOne(DueDiligenceReports::class, ['id' => 'fk_due_diligence_report_id']);
    }
    public function getOffice()
    {
        return $this->hasOne(Office::class, ['id' => 'fk_office_id']);
    }
    public function getCoordinator()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'fk_coordinator']);
    }
    public function getProvincialDirector()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'fk_provincial_director']);
    }
    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            if (empty($this->id)) {
                // $this->id = MyHelper::getUuid();
                $this->generateId();
            }
            if (empty($this->serial_number)) {
                $this->serial_number = $this->generateSerialNumber();
            }
        }
        return true;
    }

    private function generateSerialNumber()
    {
        $lastNum = YIi::$app->db->createCommand("SELECT CAST(SUBSTRING_INDEX(serial_number,'-',-1)AS UNSIGNED) as last_num 
        FROM tbl_notification_to_pay
        ORDER BY last_num DESC LIMIT 1")
            ->queryScalar();
        $num = !empty($lastNum) ? intval($lastNum) + 1 : 1;
        return date('Y') . '-' . str_pad($num, 4, '0', STR_PAD_LEFT);
    }

    public static function searchSerialNumber($page = null, $text = null, $id = null)
    {
        $limit = 5;
        $offset = ($page - 1) * $limit;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => self::findOne($id)->serial_number];
        } else if (!is_null($text)) {
            $query = self::find()
                ->addSelect([
                    new Expression("CAST(id AS CHAR(50)) as id"),
                    new Expression("serial_number as text"),
                ])
                ->from('tbl_notification_to_pay')
                ->where(['like', 'tbl_notification_to_pay.serial_number', $text])
                ->offset($offset)
                ->limit($limit);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
            $out['pagination'] = ['more' => !empty($data) ? true : false];
        }
        return $out;
    }
    // GET ALL UNLIQUIDATED NOTIFICATIONS BY MGRFR ID
    public static function getMGRFRNotificationsToPayById($id)
    {
        return self::find()
            ->addSelect([
                new Expression("CAST(tbl_notification_to_pay.id AS CHAR(50)) as notification_to_pay_id"),
                "tbl_notification_to_pay.serial_number",
                new Expression("payee.registered_name  as payee_name"),
                "due_diligence_reports.comments",
                new Expression("COALESCE(tbl_notification_to_pay.matching_grant_amount,0) as matching_grant_amount"),
                new Expression("COALESCE(tbl_notification_to_pay.equity_amount,0) as equity_amount"),
                new Expression("COALESCE(tbl_notification_to_pay.other_amount,0) as other_amount"),
            ])
            ->join('JOIN', 'due_diligence_reports', 'tbl_notification_to_pay.fk_due_diligence_report_id = due_diligence_reports.id')
            ->join('LEFT JOIN ', 'payee', 'due_diligence_reports.fk_payee_id = payee.id')
            ->andWhere("due_diligence_reports.fk_mgrfr_id = :mgrfr_id", [':mgrfr_id' => $id])
            ->andWhere("NOT EXISTS(SELECT liq.fk_notification_to_pay_id FROM tbl_mg_liquidation_items as  liq WHERE liq.is_deleted = 0 and liq.fk_notification_to_pay_id = tbl_notification_to_pay.id )")
            ->asArray()
            ->all();
    }
}
