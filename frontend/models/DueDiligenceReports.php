<?php

namespace app\models;

use Yii;
use ErrorException;
use app\components\helpers\MyHelper;
use app\behaviors\GenerateIdBehavior;
use app\behaviors\HistoryLogsBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "due_diligence_reports".
 *
 * @property int $id
 * @property string $serial_number
 * @property string $supplier_name
 * @property string $supplier_address
 * @property string $contact_person
 * @property string $contact_number
 * @property string $supplier_is_registered
 * @property int $supplier_has_business_permit
 * @property int $supplier_is_bir_registered
 * @property int $supplier_has_officer_connection
 * @property int $supplier_is_financial_capable
 * @property int $supplier_is_authorized_dealer
 * @property int $supplier_has_quality_material
 * @property int $supplier_can_comply_specs
 * @property int $supplier_has_legal_issues
 * @property string|null $supplier_nursery
 * @property string $comments
 * @property int|null $fk_mgrfr_id
 * @property int|null $fk_conducted_by
 * @property int|null $fk_noted_by
 * @property string $created_at
 *
 * @property Employee $fkConductedBy
 * @property Mgrfrs $fkMgrfr
 * @property Employee $fkNotedBy
 */
class DueDiligenceReports extends \yii\db\ActiveRecord
{
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
        return 'due_diligence_reports';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[

                'supplier_is_registered',
                'supplier_has_business_permit',
                'supplier_is_bir_registered',
                'supplier_has_officer_connection',
                'supplier_is_financial_capable',
                'supplier_is_authorized_dealer',
                'supplier_has_quality_material',
                'supplier_can_comply_specs',
                'supplier_has_legal_issues',
                'comments',
                'fk_conducted_by',
                'fk_mgrfr_id',
                'fk_noted_by',
                'fk_office_id',
                'supplier_name',
                'supplier_contact_number',
                'supplier_contact_person',
                'supplier_address'
            ], 'required'],
            [['supplier_nursery', 'comments', 'fk_office_id'], 'string'],
            [[
                'supplier_has_business_permit', 'supplier_is_bir_registered',
                'supplier_has_officer_connection', 'supplier_is_financial_capable', 'supplier_is_authorized_dealer', 'supplier_has_quality_material', 'supplier_can_comply_specs', 'supplier_has_legal_issues', 'fk_mgrfr_id', 'fk_conducted_by', 'fk_noted_by'
            ], 'integer'],
            [['created_at'], 'safe'],
            [[
                'serial_number',
                'supplier_is_registered',
                'supplier_name',
                'supplier_contact_number',
                'supplier_contact_person',

            ], 'string', 'max' => 255],
            [['supplier_address'], 'string'],
            [['serial_number'], 'unique'],
            [['fk_conducted_by'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::class, 'targetAttribute' => ['fk_conducted_by' => 'employee_id']],
            [['fk_mgrfr_id'], 'exist', 'skipOnError' => true, 'targetClass' => Mgrfrs::class, 'targetAttribute' => ['fk_mgrfr_id' => 'id']],
            [['fk_noted_by'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::class, 'targetAttribute' => ['fk_noted_by' => 'employee_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'supplier_is_registered' => 'SEC/CDA/DTI Registration',
            'supplier_has_business_permit' => 'Business Permit/ Mayor`s Permit',
            'supplier_is_bir_registered' => 'BIR Registration',
            'supplier_has_officer_connection' => 'Is the supplier related to any of the Officers of the Organization',
            'supplier_is_financial_capable' => ' Is the supplier financially capable to deliver the goods/services',
            'supplier_is_authorized_dealer' => 'Is the supplier an authorized manufacturer/dealer/distributor of the product?',
            'supplier_has_quality_material' => 'Does the supplier produce quality planting materials',
            'supplier_can_comply_specs' => 'Can the supplier meet the requirement of the organization?',
            'supplier_has_legal_issues' => 'Has the supplier been involved in legal issues?',
            'supplier_nursery' => 'Check Nursery (area of nursery, quality of seedling)',
            'comments' => 'Comments/Recommendation',
            'fk_mgrfr_id' => ' MG RFR Serial Number',
            'fk_conducted_by' => ' Conducted By',
            'fk_noted_by' => ' Noted By',
            'created_at' => 'Created at',
            'fk_office_id' => 'Office',
            'supplier_name' => 'Supplier Name',
            'supplier_address' => 'Address',
            'supplier_contact_number' => 'Contact No.',
            'supplier_contact_person' => 'Contact Person',
        ];
    }

    /**
     * Gets query for [[FkConductedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getConductedBy()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'fk_conducted_by']);
    }

    /**
     * Gets query for [[FkMgrfr]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMgrfr()
    {
        return $this->hasOne(Mgrfrs::class, ['id' => 'fk_mgrfr_id']);
    }
    public function getOffice()
    {
        return $this->hasOne(Office::class, ['id' => 'fk_office_id']);
    }

    /**
     * Gets query for [[FkNotedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNotedBy()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'fk_noted_by']);
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
        FROM due_diligence_reports
        WHERE 
        due_diligence_reports.fk_office_id = :office_id
        ORDER BY last_num DESC LIMIT 1")
            ->bindValue(':office_id', $this->fk_office_id)
            ->queryScalar();
        $num = !empty($lastNum) ? intval($lastNum) + 1 : 1;
        return strtoupper($this->office->office_name) . '-' . date('Y') . '-' . str_pad($num, 4, '0', STR_PAD_LEFT);
    }
    public function insertItems($items)
    {
        try {

            if (empty($items)) {
                throw new ErrorException('Insert Clients/Customers');
            }
            $sql = '';
            $params = [];
            $ids = array_column($items, 'id');

            if (!empty($ids)) {
                $sql  = ' AND ';
                $sql .= Yii::$app->db->queryBuilder->buildCondition(['NOT IN', 'id', $ids], $params);
            }
            Yii::$app->db->createCommand("UPDATE due_diligence_report_items
                SET due_diligence_report_items.is_deleted = 1 
                WHERE due_diligence_report_items.fk_due_diligence_report_id = :id
                AND due_diligence_report_items.is_deleted= 0
                $sql", $params)
                ->bindValue(':id', $this->id)
                ->execute();
            $itemModels = [];
            foreach ($items as $item) {
                $model = !empty($item['id']) ? DueDiligenceReportItems::findOne($item['id']) : new DueDiligenceReportItems();
                $model->attributes = $item;
                $model->fk_due_diligence_report_id = $this->id;
                $itemModels[] = $model;
            }
            foreach ($itemModels as $model) {
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Item Model Save');
                }
            }
            return true;
        } catch (ErrorException $e) {
            return $e->getMessage();
        }
    }
    public function getItemsA()
    {
        return DueDiligenceReportItems::find()->where('fk_due_diligence_report_id =:id', ['id' => $this->id])
            ->andWhere('is_deleted = 0')
            ->asArray()->all();
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
                ->from('due_diligence_reports')
                ->where(['like', 'due_diligence_reports.serial_number', $text])
                ->offset($offset)
                ->limit($limit);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
            $out['pagination'] = ['more' => !empty($data) ? true : false];
        }
        return $out;
    }
}
