<?php

namespace app\models;

use Yii;
use yii\db\Query;
use yii\db\Expression;

/**
 * This is the model class for table "tbl_rapid_fmi_sord".
 *
 * @property int $id
 * @property int $fk_fmi_subproject_id
 * @property string $reporting_period
 * @property string|null $created_at
 *
 * @property FmiSubprojects $fkFmiSubproject
 */
class RapidFmiSord extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_rapid_fmi_sord';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_fmi_subproject_id', 'reporting_period'], 'required'],
            [['fk_fmi_subproject_id'], 'integer'],
            ['fk_fmi_subproject_id', 'validateIfExists'],

            [['created_at'], 'safe'],
            [['reporting_period'], 'string', 'max' => 255],
            [['fk_fmi_subproject_id'], 'exist', 'skipOnError' => true, 'targetClass' => FmiSubprojects::class, 'targetAttribute' => ['fk_fmi_subproject_id' => 'id']],
        ];
    }
    public function validateIfExists($attribute)
    {
        $buildQry = self::find()
            ->addSelect(['id'])
            ->andWhere(['fk_fmi_subproject_id' => $this->fk_fmi_subproject_id])
            ->andWhere(['reporting_period' => $this->reporting_period]);
        if (!$this->isNewRecord) {
            $buildQry->andWhere("id !=:id", ['id' => $this->id]);
        }
        $buildQry = $buildQry->createCommand()->getRawSql();
        $qry = new Query();
        $qry->addSelect([
            new Expression("EXISTS($buildQry)")
        ]);
        if (!empty($qry->scalar())) {
            $this->addError('error', 'This Filter Already Exists.');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_fmi_subproject_id' => 'Fmi Subproject',
            'reporting_period' => 'Reporting Period',
            'created_at' => 'Created At',
        ];
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
}
