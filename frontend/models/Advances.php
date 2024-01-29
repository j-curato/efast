<?php

namespace app\models;

use Yii;
use ErrorException;
use yii\helpers\ArrayHelper;
use app\behaviors\HistoryLogsBehavior;

/**
 * This is the model class for table "advances".
 *
 * @property int $id
 * @property int|null $sub_account1_id
 * @property string|null $province
 * @property string|null $particular
 *
 * @property CashDisbursement $cashDisbursement
 * @property SubAccounts1 $subAccount1
 */
class Advances extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            HistoryLogsBehavior::class
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'advances';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['province', 'reporting_period'], 'string', 'max' => 50],
            [['bank_account_id', 'reporting_period'], 'required'],
            [['bank_account_id'], 'integer'],
            [['fk_office_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'province' => 'Province',
            'reporting_period' => 'Reporting Period',
            'nft_number' => 'NFT Number',
            'created_at' => 'Created At',
            'bank_account_id' => 'Bank Account',
            'dv_aucs_id' => 'Dv Aucs',
            'fk_office_id' => 'Office'
        ];
    }

    public function getAdvancesEntries()
    {
        return $this->hasMany(AdvancesEntries::class, ['advances_id' => 'id']);
    }
    public function getOffice()
    {
        return $this->hasOne(Office::class, ['id' => 'fk_office_id']);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            if ($this->isNewRecord) {
                if (empty($this->nft_number)) {
                    $this->nft_number = $this->generateNftNumber();
                }
            }
            return true;
        }
        return false;
    }
    public function generateNftNumber()
    {
        $q = Yii::$app->db->createCommand("SELECT CAST(substring_index(nft_number, '-', -1)AS UNSIGNED) as q 
            from advances 
            WHERE 
            nft_number NOT LIKE 'S%'
            AND nft_number NOT LIKE 'a%'
            AND nft_number NOT LIKE 'P%'
            AND nft_number NOT LIKE 'R%'
            ORDER BY q DESC
            LIMIT 1")->queryScalar();

        $num = !empty($q) ? $num = (int) $q + 1 : 1;
        return date('Y') . '-' . str_pad($num, '0', 4, STR_PAD_LEFT);
    }
    public function insertItems($items, $book_id)
    {
        try {
            $itemModels = [];
            $deleteItems = $this->deleteItems(ArrayHelper::getColumn($items, 'id'));
            if ($deleteItems !== true) {
                throw new ErrorException($deleteItems);
            }
            foreach ($items as $index => $item) {
                $model = !empty($item['id']) ? AdvancesEntries::findOne($item['id']) : new AdvancesEntries();
                $model->attributes = $item;
                $model->advances_id = $this->id;
                $model->book_id = $book_id;
                $model->fund_source_type = $model->fundSourceType->name ?? null;
                $model->report_type = $model->advancesReportType->name ?? null;
                $cashId = $this->getCashId();
                if (!empty($cashId)) {
                    $model->cash_disbursement_id = $cashId;
                    if ($model->isNewRecord) {
                        $model->is_deleted = 0;
                    }
                }
                $itemModels[] = $model;
            }

            foreach ($itemModels as $model) {
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Advances Item Model Save Failed');
                }
            };

            return true;
        } catch (ErrorException $e) {
            return $e->getMessage();
        }
    }

    private function getCashId()
    {

        return Yii::$app->db->createCommand("SELECT
        cash_disbursement.id
        FROM cash_disbursement
    JOIN cash_disbursement_items ON cash_disbursement.id = cash_disbursement_items.fk_cash_disbursement_id
        WHERE 
            cash_disbursement.is_cancelled = 0
            AND NOT EXISTS (SELECT cncl_chks.parent_disbursement
            FROM cash_disbursement as cncl_chks 
            WHERE cncl_chks.parent_disbursement = cash_disbursement.id 
            AND cncl_chks.is_cancelled = 1 
            AND cncl_chks.parent_disbursement IS NOT NULL
            ) 
                    AND cash_disbursement_items.is_deleted = 0
                    AND cash_disbursement_items.fk_dv_aucs_id = :dv_id")
            ->bindValue(':dv_id', $this->dv_aucs_id)
            ->queryScalar();
    }
    private function deleteItems($items)
    {
        $queryItems  = Yii::$app->db->createCommand("SELECT advances_entries.id FROM advances_entries WHERE advances_id  = :id
        AND is_deleted != 1")
            ->bindValue(':id', $this->id)
            ->queryAll();
        $toDelete = array_diff(array_column($queryItems, 'id'), $items);
        if (!empty($toDelete)) {
            $params = [];
            $sql  = ' AND ';
            $sql .= Yii::$app->db->queryBuilder->buildCondition(['IN', 'id', $toDelete], $params);
            Yii::$app->db->createCommand("UPDATE advances_entries
                SET advances_entries.is_deleted = 1 
                WHERE advances_entries.advances_id = :id
                AND advances_entries.is_deleted != 0
                $sql", $params)
                ->bindValue(':id', $this->id)
                ->execute();
        }
        return true;
    }
}
