<?php

namespace app\models;

use app\behaviors\GenerateIdBehavior;
use app\behaviors\HistoryLogsBehavior;
use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "property".
 *
 * @property string $property_number
 * @property int|null $book_id
 * @property int|null $unit_of_measure_id
 * @property int|null $employee_id
 * @property string|null $iar_number
 * @property string|null $article
 * @property string|null $model
 * @property string|null $serial_number
 * @property int|null $quantity
 * @property float|null $acquisition_amount
 *
 * @property Par $par
 */
class Property extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            HistoryLogsBehavior::class,
            GenerateIdBehavior::class,
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'property';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                // 'property_number',
                'date',
                'unit_of_measure_id',
                'acquisition_amount',
                'serial_number',
                // 'description',
                // 'fk_ssf_sp_num_id',
                'fk_office_id',
                // 'is_ssf',
                'employee_id',
                // 'fk_property_article_id'
            ], 'required'],
            [['date'], 'string'],
            [[
                'book_id',
                'unit_of_measure_id',
                'quantity',
                'estimated_life',
                'fk_ssf_category_id',
                'id',
                'fk_ssf_sp_num_id',
                'fk_office_id',
                'is_ssf',
                'employee_id',
                'ppe_year',
                'fk_property_article_id'
            ], 'integer'],
            [['acquisition_amount', 'salvage_value'], 'number'],
            [['id'], 'unique'],
            [['serial_number'], 'safe'],
            [['article', 'description', 'object_code'], 'string'],
            [['property_number', 'iar_number', 'model', 'province', 'ppe_type'], 'string', 'max' => 255],
            ['fk_ssf_category_id', 'required', 'when' => function ($model) {
                return $model->ppe_type == 'SSF';
            }, 'whenClient' => "function (attribute, value) {
                return $('#fk_ssf_category_id').val() == 'SSF';
            }"],
            [['property_number'], 'unique'],
            [[
                'property_number',
                'article',
                'model',
                'serial_number',
                'description',
                'object_code',
                'province',

            ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'property_number' => 'Property Number',
            'book_id' => 'Book ',
            'unit_of_measure_id' => 'Unit Of Measure ',
            'employee_id' => 'Property Custodian',
            'iar_number' => 'IAR Number',
            'article' => 'Article',
            'description' => 'Description',
            'model' => 'Model',
            'serial_number' => 'Serial Number (put "N/A" if not applicable)',
            'quantity' => 'Quantity',
            'acquisition_amount' => 'Acquisition Amount',
            'date' => 'Date Acquired',
            'id' => 'ID',
            'object_code' => 'Object Code',
            'salvage_value' => 'Salvage Value',
            'estimated_life' => 'Estimated Useful Life',
            'province' => 'Province',
            'fk_ssf_category_id' => 'SSF Category',
            'ppe_type' => 'PPE Type',
            'fk_ssf_sp_num_id' => 'SSF SP No.',
            'fk_office_id' => 'Office',
            'is_ssf' => 'SSF/Non-SSF',
            'ppe_year' => 'PPE year',
            'fk_property_article_id' => 'Article',

        ];
    }

    /**
     * Gets query for [[Par]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPar()
    {
        return $this->hasOne(Par::class, ['property_number' => 'property_number']);
    }
    public function getBook()
    {
        return $this->hasOne(Books::class, ['id' => 'book_id']);
    }
    public function getEmployee()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'employee_id']);
    }

    public function getUnitOfMeasure()
    {
        return $this->hasOne(UnitOfMeasure::class, ['id' => 'unit_of_measure_id']);
    }
    public function getSsfCategory()
    {
        return $this->hasOne(SsfCategory::class, ['id' => 'fk_ssf_category_id']);
    }
    public function getSsfSpNum()
    {
        return $this->hasOne(SsfSpNum::class, ['id' => 'fk_ssf_sp_num_id']);
    }
    public function getOffice()
    {
        return $this->hasOne(Office::class, ['id' => 'fk_office_id']);
    }
    public function getPropertyArticle()
    {
        return $this->hasOne(PropertyArticles::class, ['id' => 'fk_property_article_id']);
    }


    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                if (empty($this->property_number)) {

                    $this->property_number = $this->generatePropertyNumber();
                }
            }
            return true;
        }
        return false;
    }
    private function generatePropertyNumber()
    {

        $lastNum = self::find()
            ->addSelect([
                new Expression("CAST(SUBSTRING_INDEX(property_number,'-',-1)AS UNSIGNED) as last_num")
            ])
            ->andWhere(['fk_office_id' => $this->fk_office_id])
            ->andWhere('property_number LIKE :property_number', ['property_number' => $this->office->office_name . '-PPE%'])
            ->orderBy("last_num DESC")
            ->limit(1)
            ->scalar();
        $num = !empty($lastNum) ? $lastNum + 1 : 1;
        return $this->office->office_name . '-PPE-' . str_pad($num, 4, '0', STR_PAD_LEFT);
    }
}
