<?php

namespace app\models;

use Yii;
use DateTime;
use ErrorException;
use yii\db\Expression;
use app\behaviors\GenerateIdBehavior;
use app\behaviors\HistoryLogsBehavior;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Exp;

/**
 * This is the model class for table "tbl_fmi_lgu_liquidations".
 *
 * @property int $id
 * @property int|null $fk_fmi_subproject_id
 * @property string|null $serial_number
 * @property int $fk_office_id
 * @property string $reporting_period
 * @property string $created_at
 *
 * @property FmiLguLiquidationItems[] $tblFmiLguLiquidationItems
 * @property FmiSubprojects $fkFmiSubproject
 * @property Office $fkOffice
 */
class FmiLguLiquidations extends \yii\db\ActiveRecord
{

    public function behaviors()
    {
        return [
            'history' => [
                'class' => HistoryLogsBehavior::class
            ],
            'generateId' => [
                'class' => GenerateIdBehavior::class
            ]
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_fmi_lgu_liquidations';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_office_id', 'reporting_period'], 'required'],
            [['id', 'fk_fmi_subproject_id', 'fk_office_id'], 'integer'],
            [['created_at'], 'safe'],
            [['serial_number', 'reporting_period'], 'string', 'max' => 255],
            [['serial_number'], 'unique'],
            [['id'], 'unique'],
            [['fk_fmi_subproject_id'], 'exist', 'skipOnError' => true, 'targetClass' => FmiSubprojects::class, 'targetAttribute' => ['fk_fmi_subproject_id' => 'id']],
            [['fk_office_id'], 'exist', 'skipOnError' => true, 'targetClass' => Office::class, 'targetAttribute' => ['fk_office_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_fmi_subproject_id' => 'FMI Subproject ',
            'serial_number' => 'Serial Number',
            'fk_office_id' => ' Office ',
            'reporting_period' => 'Reporting Period',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[FmiLguLiquidationItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFmiLguLiquidationItems()
    {
        return $this->hasMany(FmiLguLiquidationItems::class, ['fk_fmi_lgu_liquidation_id' => 'id'])
            ->andWhere(['is_deleted' => false]);
    }

    /**
     * Gets query for [[FkFmiSubproject]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFmiSubproject()
    {
        return $this->hasOne(FmiSubprojects::class, ['id' => 'fk_fmi_subproject_id']);
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
    public function generateSerialNumber()
    {

        $year = DateTime::createFromFormat('Y-m', $this->reporting_period)->format('Y');
        $lastNum  = self::find()
            ->addSelect([
                new Expression("CAST(SUBSTRING_INDEX(serial_number,'-',-1)AS UNSIGNED) as last_num")
            ])
            ->orderBy(['last_num' => SORT_DESC])
            ->andWhere([
                'LIKE',
                'serial_number',
                $year
            ])
            ->andWhere(['fk_office_id' => $this->fk_office_id])
            ->limit(1)
            ->scalar();
        $num = !empty($lastNum) ? intval($lastNum) + 1 : 1;

        return strtoupper($this->office->office_name) . '-' . $year . '-' . str_pad($num, 5, '0', STR_PAD_LEFT);
    }
    public function insertItems($items, $isCreate = false)
    {

        try {

            if (!$this->isNewRecord) {
                $this->deleteItems($items);
            }
            $itemModels = [];;
            foreach ($items as $item) {
                $model = !empty($item['id']) ? FmiLguLiquidationItems::findOne($item['id']) : new FmiLguLiquidationItems();
                $model->attributes = $item;

                if ($isCreate) {
                    $model->reporting_period = $this->reporting_period;
                }
                $model->fk_fmi_lgu_liquidation_id = $this->id;
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
    public function getFmiLguLiquidationItemsA($selectOptions = [])
    {
        $qry =  $this->getFmiLguLiquidationItems();
        if (!empty($selectOptions)) {
            $qry->addSelect($selectOptions);
        }
        return $qry->asArray()->all();
    }
    public function deleteItems($items)
    {


        $toDelete = array_diff(array_column($this->getFmiLguLiquidationItemsA(['id']), 'id'), array_column($items, 'id'));

        if (!empty($toDelete)) {
            FmiLguLiquidationItems::updateAll(
                ['is_deleted' => true],
                [
                    'id' => $toDelete,
                    'is_deleted' => false,
                    'fk_fmi_lgu_liquidation_id' => $this->id
                ]
            );
        }
        return true;
    }
}
