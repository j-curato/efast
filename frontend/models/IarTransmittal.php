<?php

namespace app\models;

use Yii;
use DateTime;
use ErrorException;
use yii\helpers\ArrayHelper;
use app\behaviors\GenerateIdBehavior;
use app\behaviors\HistoryLogsBehavior;

/**
 * This is the model class for table "{{%iar_transmittal}}".
 *
 * @property int $id
 * @property string $serial_number
 * @property string|null $date
 * @property string $created_at
 *
 * @property IarTransmittalItems[] $iarTransmittalItems
 */
class IarTransmittal extends \yii\db\ActiveRecord
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
        return '{{%iar_transmittal}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_approved_by', 'date'], 'required'],
            [[
                'id',
                'fk_approved_by',
                'fk_officer_in_charge',
            ], 'integer'],
            [['date', 'created_at'], 'safe'],
            [['serial_number'], 'string', 'max' => 255],
            [['serial_number'], 'unique'],
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
            'serial_number' => 'Serial Number',
            'date' => 'Date',
            'created_at' => 'Created At',
            'fk_approved_by' => 'Approved By',
            'fk_officer_in_charge' => 'Officer In-Charge',
        ];
    }

    /**
     * Gets query for [[IarTransmittalItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIarTransmittalItems()
    {
        return $this->hasMany(IarTransmittalItems::class, ['fk_iar_transmittal_id' => 'id']);
    }
    public function getApprovedBy()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'fk_approved_by']);
    }
    public function getOfficerInCharge()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'fk_officer_in_charge']);
    }

    public function beforeSave($insert)
    {
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
    public function generateSerialNumber()
    {
        $year = DateTime::createFromFormat('Y-m-d', $this->date)->format('Y');
        $q = Yii::$app->db->createCommand("SELECT CAST(SUBSTRING_INDEX(serial_number,'-',-1) AS UNSIGNED) as last_num 
        FROM iar_transmittal
        ORDER BY last_num DESC LIMIT 1")
            ->queryScalar();
        $last_num = !empty($q) ? intval($q) + 1 : 1;
        return 'RO-' . $year . '-'  . str_pad($last_num, 4, '0', STR_PAD_LEFT);
    }

    public function insertItems($items)
    {

        try {
            if (empty($items)) {
                throw new ErrorException('Insert Items');
            }
            $deleteItems = $this->deleteItems(ArrayHelper::getColumn($items, 'id'));

            $itemModels = [];
            foreach ($items as $item) {
                $model = !empty($item['id']) ? IarTransmittalItems::findOne($item['id']) : new IarTransmittalItems();
                $model->attributes = $item;
                $model->fk_iar_transmittal_id = $this->id;
                $itemModels[] = $model;
            }

            foreach ($itemModels as $model) {


                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Item Model Save Failed');
                }
            }
            return true;
        } catch (ErrorException $e) {
            return $e->getMessage();
        }
    }
    private function deleteItems($items)
    {
        $queryItems  = Yii::$app->db->createCommand("SELECT iar_transmittal_items.id FROM iar_transmittal_items WHERE fk_iar_transmittal_id  = :id
        AND is_deleted = 0")
            ->bindValue(':id', $this->id)
            ->queryAll();
        $toDelete = array_diff(array_column($queryItems, 'id'), $items);
        if (!empty($toDelete)) {
            $params = [];
            $sql  = ' AND ';
            $sql .= Yii::$app->db->queryBuilder->buildCondition(['IN', 'id', $toDelete], $params);
            Yii::$app->db->createCommand("UPDATE iar_transmittal_items
                SET iar_transmittal_items.is_deleted = 1 
                WHERE iar_transmittal_items.fk_iar_transmittal_id = :id
                AND iar_transmittal_items.is_deleted= 0
                $sql", $params)
                ->bindValue(':id', $this->id)
                ->execute();
        }
        return true;
    }
}
