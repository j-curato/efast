<?php

namespace app\models;

use Yii;
use ErrorException;
use yii\db\Expression;
use app\behaviors\GenerateIdBehavior;
use app\behaviors\HistoryLogsBehavior;
use app\models\FmiSubprojectOrganizations;

/**
 * This is the model class for table "tbl_fmi_subprojects".
 *
 * @property int $id
 * @property int|null $fk_province_id
 * @property int|null $fk_municipality_id
 * @property int|null $fk_barangay_id
 * @property string|null $purok
 * @property int|null $fk_fmi_batch_id
 * @property int|null $project_duration
 * @property int|null $project_road_length
 * @property string|null $project_start_date
 * @property float|null $grant_amount
 * @property float|null $equity_amount
 * @property string|null $bank_account_name
 * @property string|null $bank_account_number
 * @property string $created_at
 *
 * @property FmiSubprojectOrganizations[] $tblFmiSubprojectOrganizations
 * @property Barangays $fkBarangay
 * @property FmiBatches $fkFmiBatch
 * @property Municipalities $fkMunicipality
 * @property Provinces $fkProvince
 */
class FmiSubprojects extends \yii\db\ActiveRecord
{

    public function behaviors()
    {
        return [
            'idGenerator' => [
                'class' => GenerateIdBehavior::class
            ],
            'historyLogs' => [
                'class' => HistoryLogsBehavior::class
            ]
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_fmi_subprojects';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'fk_province_id',
                'fk_municipality_id',
                'fk_barangay_id',
                'project_duration',
                'project_road_length',
                'project_start_date',
                'grant_amount',
                'equity_amount',
                'bank_account_name',
                'bank_account_number',
                'fk_office_id',
                'fk_bank_branch_detail_id',
                'project_name'
            ], 'required'],
            [[
                'id', 'fk_province_id',
                'fk_bank_branch_detail_id',
                'fk_municipality_id', 'fk_barangay_id', 'fk_fmi_batch_id', 'project_duration', 'project_road_length', 'fk_office_id'
            ], 'integer'],
            [['purok', 'serial_number'], 'string'],
            [['project_start_date', 'created_at'], 'safe'],
            [['grant_amount', 'equity_amount'], 'number'],
            [['project_name'], 'string'],
            [['bank_account_name', 'bank_account_number'], 'string', 'max' => 255],
            [['id'], 'unique'],
            [['fk_barangay_id'], 'exist', 'skipOnError' => true, 'targetClass' => Barangays::class, 'targetAttribute' => ['fk_barangay_id' => 'id']],
            [['fk_fmi_batch_id'], 'exist', 'skipOnError' => true, 'targetClass' => FmiBatches::class, 'targetAttribute' => ['fk_fmi_batch_id' => 'id']],
            [['fk_municipality_id'], 'exist', 'skipOnError' => true, 'targetClass' => Municipalities::class, 'targetAttribute' => ['fk_municipality_id' => 'id']],
            [['fk_province_id'], 'exist', 'skipOnError' => true, 'targetClass' => Provinces::class, 'targetAttribute' => ['fk_province_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_province_id' => ' Province ',
            'fk_municipality_id' => ' Municipality ',
            'fk_barangay_id' => ' Barangay ',
            'purok' => 'Purok',
            'fk_fmi_batch_id' => ' Fmi Batch ',
            'project_duration' => 'Project Duration in Months',
            'project_road_length' => 'Project Road Length',
            'project_start_date' => 'Project Start Date',
            'grant_amount' => 'Grant Amount',
            'equity_amount' => 'Equity Amount',
            'bank_account_name' => 'Bank Account Name',
            'bank_account_number' => 'Bank Account Number',
            'created_at' => 'Created At',
            'serial_number' => 'Serial Number',
            'fk_office_id' => 'Office',
            'project_name' => 'Project Name',
            'fk_bank_branch_detail_id' => 'Bank Branch',

        ];
    }

    /**
     * Gets query for [[FmiSubprojectOrganizations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFmiSubprojectOrganizations()
    {
        return $this->hasMany(FmiSubprojectOrganizations::class, [
            'fk_fmi_subproject_id' => 'id',
        ])
            ->andWhere(['is_deleted' => false]);
    }
    public function getBankBranchDetail()
    {
        return $this->hasOne(BankBranchDetails::class, ['id' => 'fk_bank_branch_detail_id']);
    }
    public function getFmiSubprojectOrganizationsA($selectOptions = [])
    {
        $query =  $this->getFmiSubprojectOrganizations();
        if (!empty($selectOptions)) {
            $query->addSelect($selectOptions);
        }
        return $query
            ->asArray()
            ->all();
    }
    /**
     * Gets query for [[FkBarangay]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBarangay()
    {
        return $this->hasOne(Barangays::class, ['id' => 'fk_barangay_id']);
    }

    /**
     * Gets query for [[FkFmiBatch]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFmiBatch()
    {
        return $this->hasOne(FmiBatches::class, ['id' => 'fk_fmi_batch_id']);
    }
    /**
     * Gets query for [[Municipality]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMunicipality()
    {
        return $this->hasOne(Municipalities::class, ['id' => 'fk_municipality_id']);
    }

    /**
     * Gets query for [[Province]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProvince()
    {
        return $this->hasOne(Provinces::class, ['id' => 'fk_province_id']);
    }

    public function getOffice()
    {
        return $this->hasOne(Office::class, ['id' => 'fk_office_id']);
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
    private function generateSerialNumber()
    {
        $lastNum   = Yii::$app->db->createCommand("SELECT CAST(SUBSTRING_INDEX(serial_number,'-',-1) AS UNSIGNED) as last_num 
        FROM tbl_fmi_subprojects 
        ORDER BY last_num DESC LIMIT 1")->queryScalar();
        $lastNum   = !empty($lastNum) ? $lastNum + 1 : 1;
        return  strtoupper($this->office->office_name) . '-' . date('Y') . '-' . str_pad($lastNum, 5, '0', STR_PAD_LEFT);
    }
    public function insertItems($items)
    {
        try {

            if (!$this->isNewRecord) {
                $this->deleteItems($items);
            }
            $itemModels = [];
            foreach ($items as $item) {
                $model = !empty($item['id']) ? FmiSubprojectOrganizations::findOne($item['id']) : new FmiSubprojectOrganizations();
                $model->attributes = $item;
                $model->fk_fmi_subproject_id = $this->id;
                $itemModels[] = $model;
            }
            foreach ($itemModels as $model) {

                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException("Item Model Save Failed");
                }
            }
            return true;
        } catch (ErrorException $e) {
            return $e->getMessage();
        }
    }
    public function deleteItems($items)
    {
        $entriesToDelete = FmiSubprojectOrganizations::find()
            ->addSelect(['id'])
            ->where(['fk_fmi_subproject_id' => $this->id, 'is_deleted' => 0])
            ->asArray()
            ->all();

        $toDelete = array_diff(array_column($entriesToDelete, 'id'), array_column($items, 'id'));
        if (!empty($toDelete)) {
            FmiSubprojectOrganizations::updateAll(
                ['is_deleted' => 1],
                ['fk_fmi_subproject_id' => $this->id, 'is_deleted' => 0, 'id' => $toDelete]
            );
        }
        return true;
    }
    public static function searchSubproject($page = 1, $text = null, $id = null)
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
                ->where(['like', 'tbl_fmi_subprojects.serial_number', $text])
                ->offset($offset)
                ->limit($limit);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
            $out['pagination'] = ['more' => !empty($data) ? true : false];
        }
        return $out;
    }
    public function getDetails()
    {

        return self::find()
            ->addSelect([
                "provinces.province_name",
                "municipalities.municipality_name",
                "barangays.barangay_name",
                "tbl_fmi_subprojects.purok",
                "tbl_fmi_subprojects.grant_amount",
                "tbl_fmi_subprojects.equity_amount",
                "tbl_fmi_batches.batch_name",
                "tbl_fmi_subprojects.bank_account_name",
                "tbl_fmi_subprojects.bank_account_number",
                "tbl_fmi_subprojects.project_name",


            ])
            ->join("LEFT JOIN", "provinces", "tbl_fmi_subprojects.fk_province_id = provinces.id")
            ->join("LEFT JOIN", "municipalities", "tbl_fmi_subprojects.fk_municipality_id  = municipalities.id")
            ->join("LEFT JOIN", "barangays", "tbl_fmi_subprojects.fk_barangay_id  = barangays.id")
            ->join("LEFT JOIN", "tbl_fmi_batches", "tbl_fmi_subprojects.fk_fmi_batch_id = tbl_fmi_batches.id")
            ->andWhere(['tbl_fmi_subprojects.id' => $this->id])
            ->asArray()
            ->one();
    }
    public function getBeginningBalance($reportingPeriod)
    {

        $liquidatedFilters = [
            [
                'value' => $reportingPeriod,
                'operator' => '<',
                'column' => 'tbl_fmi_lgu_liquidation_items.reporting_period'
            ]
        ];
        $FundReleaseFilter = [
            [
                'value' => $reportingPeriod,
                'operator' => '<',
                'column' => 'cash_disbursement.reporting_period'
            ]
        ];
        return self::find()
            ->addSelect([
                "tbl_fmi_subprojects.id",
                "liquidated.*",
                "total_funds.*"
            ])
            ->leftJoin(
                ['liquidated' => static::queryBuildLiquidated($liquidatedFilters)],
                'tbl_fmi_subprojects.id = liquidated.fk_fmi_subproject_id'
            )
            ->leftJoin(
                ['total_funds' => static::queryBuildFundRelease($FundReleaseFilter)],
                'tbl_fmi_subprojects.id = total_funds.fk_fmi_subproject_id'
            )
            ->andWhere(['id' => $this->id])
            ->asArray()
            ->one();
    }
    private function queryBuildLiquidated($filters = null)
    {
        $qry =  FmiLguLiquidations::find()
            ->addSelect([
                "tbl_fmi_lgu_liquidations.fk_fmi_subproject_id",
                new Expression("SUM(tbl_fmi_lgu_liquidation_items.equity_amount) as total_liquidated_equity"),
                new Expression("SUM(tbl_fmi_lgu_liquidation_items.grant_amount) as total_liquidated_grant")
            ])
            ->join("JOIN", "tbl_fmi_lgu_liquidation_items",  "tbl_fmi_lgu_liquidations.id = tbl_fmi_lgu_liquidation_items.fk_fmi_lgu_liquidation_id")
            ->andWhere(["tbl_fmi_lgu_liquidation_items.is_deleted" => false]);
        if (!empty($filters)) {
            foreach ($filters as $val) {
                $qry->andWhere([$val['operator'], $val['column'], $val['value']]);
            }
        }
        return $qry->groupBy("tbl_fmi_lgu_liquidations.fk_fmi_subproject_id");
    }
    private function queryBuildFundRelease($filter = null)
    {

        $qry =  FmiFundReleases::find()
            ->addSelect([
                "tbl_fmi_fund_releases.fk_fmi_subproject_id",
                new Expression("COALESCE(SUM(dv_aucs_entries.amount_disbursed),0) as amount_disbursed"),
                new Expression("COALESCE(SUM(dv_aucs_entries.vat_nonvat) ,0)as vat_nonvat"),
                new Expression("COALESCE(SUM(dv_aucs_entries.ewt_goods_services),0) as ewt_goods_services"),
                new Expression("COALESCE(SUM(dv_aucs_entries.compensation),0) as compensation"),
                new Expression("COALESCE(SUM(dv_aucs_entries.total_withheld),0) as total_withheld"),
                new Expression("COALESCE(SUM(dv_aucs_entries.other_trust_liabilities),0) as other_trust_liabilities")
            ])
            ->join("JOIN", "cash_disbursement", "tbl_fmi_fund_releases.fk_cash_disbursement_id = cash_disbursement.id")
            ->join("JOIN", "cash_disbursement_items", "cash_disbursement.id = cash_disbursement_items.fk_cash_disbursement_id")
            ->join("JOIN", "dv_aucs", "cash_disbursement_items.fk_dv_aucs_id = dv_aucs.id")
            ->join("JOIN", "dv_aucs_entries", "dv_aucs.id = dv_aucs_entries.dv_aucs_id")
            ->andWhere(["cash_disbursement_items.is_deleted"  => false])
            ->andWhere(["dv_aucs_entries.is_deleted"  => false]);
        if (!empty($filters)) {
            foreach ($filters as $val) {
                $qry->andWhere([$val['operator'], $val['column'], $val['value']]);
            }
        }
        return  $qry->groupBy("tbl_fmi_fund_releases.fk_fmi_subproject_id");
    }
    public function getLiquidationsA($reportingPeriod)
    {
        return FmiLguLiquidations::find()
            ->addSelect([
                "tbl_fmi_lgu_liquidations.serial_number",
                "tbl_fmi_lgu_liquidation_items.reporting_period",
                new Expression("tbl_fmi_lgu_liquidation_items.`date` as check_date"),
                "tbl_fmi_lgu_liquidation_items.check_number",
                "tbl_fmi_lgu_liquidation_items.payee",
                "tbl_fmi_lgu_liquidation_items.particular",
                "tbl_fmi_lgu_liquidation_items.equity_amount",
                "tbl_fmi_lgu_liquidation_items.grant_amount",
                "tbl_fmi_lgu_liquidation_items.other_fund_amount",
                "tbl_fmi_lgu_liquidation_items.date",
            ])
            ->join("JOIN", "tbl_fmi_lgu_liquidation_items", "tbl_fmi_lgu_liquidations.id = tbl_fmi_lgu_liquidation_items.fk_fmi_lgu_liquidation_id")
            ->andWhere(['tbl_fmi_lgu_liquidation_items.reporting_period' => $reportingPeriod])
            ->asArray()
            ->all();
    }


    public function getFundReleasesA($reportingPeriod)
    {
    }
}
