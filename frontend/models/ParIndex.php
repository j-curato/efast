<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%par_index}}".
 *
 * @property int $id
 * @property string $par_number
 * @property string|null $property_number
 * @property string|null $article
 * @property string|null $description
 * @property string|null $province
 * @property float|null $acquisition_amount
 * @property string|null $date
 * @property string|null $location
 * @property string|null $accountable_officer
 * @property string|null $actual_user
 * @property string|null $issued_by
 * @property string|null $remarks
 */
class ParIndex extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%par_index}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'par_number'], 'required'],
            [['id'], 'integer'],
            [['article', 'description', 'accountable_officer', 'actual_user', 'issued_by'], 'string'],
            [['acquisition_amount'], 'number'],
            [['par_number', 'property_number', 'province', 'date', 'location', 'remarks'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'par_number' => 'Par Number',
            'property_number' => 'Property Number',
            'article' => 'Article',
            'description' => 'Description',
            'province' => 'Province',
            'acquisition_amount' => 'Acquisition Amount',
            'date' => 'Date',
            'location' => 'Location',
            'accountable_officer' => 'Accountable Officer',
            'actual_user' => 'Actual User',
            'issued_by' => 'Issued By',
            'remarks' => 'Remarks',
        ];
    }
}
