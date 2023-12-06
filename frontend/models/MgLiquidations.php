<?php

namespace app\models;

use Yii;
use ErrorException;
use app\models\Office;
use yii\db\Expression;
use app\behaviors\GenerateIdBehavior;
use app\behaviors\HistoryLogsBehavior;

/**
 * This is the model class for table "tbl_mg_liquidations".
 *
 * @property int $id
 * @property string $serial_number
 * @property string $reporting_period
 * @property int $fk_mgrfr_id
 * @property string $created_at
 *
 * @property MgLiquidationItems[] $tblMgLiquidationItems
 * @property Mgrfrs $fkMgrfr
 */
class MgLiquidations extends \yii\db\ActiveRecord
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
        return 'tbl_mg_liquidations';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['reporting_period', 'fk_mgrfr_id', 'fk_office_id',], 'required'],
            [['id', 'fk_mgrfr_id', 'fk_office_id'], 'integer'],
            [['created_at'], 'safe'],
            [['serial_number', 'reporting_period',], 'string', 'max' => 255],
            [['serial_number'], 'unique'],
            [['id'], 'unique'],
            [['fk_mgrfr_id'], 'exist', 'skipOnError' => true, 'targetClass' => Mgrfrs::class, 'targetAttribute' => ['fk_mgrfr_id' => 'id']],
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
            'reporting_period' => 'Reporting Period',
            'fk_mgrfr_id' => 'MRGRFs',
            'fk_office_id' => 'Office ',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[MgLiquidationItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMgLiquidationItems()
    {
        return $this->hasMany(MgLiquidationItems::class, ['fk_mg_liquidation_id' => 'id']);
    }
    public function getOffice()
    {
        return $this->hasOne(Office::class, ['id' => 'fk_office_id']);
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
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            if ($this->isNewRecord) {
                if (empty($this->id)) {
                    $this->generateId();
                }
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

        $lastNum = Yii::$app->db->createCommand("SELECT CAST(SUBSTRING_INDEX(serial_number,'-',-1)AS UNSIGNED) as last_num 
        FROM tbl_mg_liquidations
        WHERE 
        tbl_mg_liquidations.fk_office_id = :office_id
        ORDER BY last_num DESC LIMIT 1")
            ->bindValue(':office_id', $this->fk_office_id)
            ->queryScalar();
        $num = !empty($lastNum) ? intval($lastNum) + 1 : 1;
        return strtoupper($this->office->office_name) . '-' . date('Y') . '-' . str_pad($num, 4, '0', STR_PAD_LEFT);
    }
    public function insertItems($items)
    {
        try {
            if (!$this->isNewRecord) {
                $this->deleteItems($items);
            }
            $itemModels = [];
            foreach ($items as $item) {
                $model = !empty($item['id']) ?  MgLiquidationItems::findOne($item['id']) : new MgLiquidationItems();
                $model->attributes = $item;
                $model->fk_mg_liquidation_id = $this->id;
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
    private function deleteItems($items)
    {
        $queryItems  = Yii::$app->db->createCommand("SELECT tbl_mg_liquidation_items.id FROM tbl_mg_liquidation_items WHERE fk_mg_liquidation_id  = :id
        AND is_deleted = 0")
            ->bindValue(':id', $this->id)
            ->queryAll();
        $toDelete = array_diff(array_column($queryItems, 'id'), array_column($items, 'id'));
        if (!empty($toDelete)) {
            $params = [];
            $sql  = ' AND ';
            $sql .= Yii::$app->db->queryBuilder->buildCondition(['IN', 'id', $toDelete], $params);
            Yii::$app->db->createCommand("UPDATE tbl_mg_liquidation_items
                SET tbl_mg_liquidation_items.is_deleted = 1 
                WHERE tbl_mg_liquidation_items.fk_mg_liquidation_id = :id
                AND tbl_mg_liquidation_items.is_deleted= 0
                $sql", $params)
                ->bindValue(':id', $this->id)
                ->execute();
        }
        return true;
    }

    public function getItems()
    {

        return MgLiquidationItems::find()
            ->addSelect([
                new Expression("CAST(tbl_mg_liquidation_items.id AS CHAR(50)) as id"),
                new Expression("CAST(tbl_notification_to_pay.id AS CHAR(50)) as notification_to_pay_id"),
                "tbl_notification_to_pay.serial_number",
                new Expression("due_diligence_reports.supplier_name as payee_name"),
                "due_diligence_reports.comments",
                new Expression("COALESCE(tbl_notification_to_pay.matching_grant_amount,0) as matching_grant_amount"),
                new Expression("COALESCE(tbl_notification_to_pay.equity_amount,0) as equity_amount"),
                new Expression("COALESCE(tbl_notification_to_pay.other_amount,0) as other_amount"),
                "tbl_mg_liquidation_items.date",
                "tbl_mg_liquidation_items.dv_number",
            ])
            ->join('JOIN', 'tbl_notification_to_pay', 'tbl_mg_liquidation_items.fk_notification_to_pay_id = tbl_notification_to_pay.id')
            ->join('JOIN', 'due_diligence_reports', 'tbl_notification_to_pay.fk_due_diligence_report_id = due_diligence_reports.id')
            ->andWhere("tbl_mg_liquidation_items.fk_mg_liquidation_id = :id", [':id' => $this->id])
            ->andWhere('tbl_mg_liquidation_items.is_deleted = 0')
            ->asArray()
            ->all();
    }

}
