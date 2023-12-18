<?php

namespace app\models;

use app\behaviors\GenerateIdBehavior;
use app\behaviors\HistoryLogsBehavior;
use Yii;
use yii\db\Expression;
use yii\db\Query;

/**
 * This is the model class for table "tbl_rapid_mg_sord".
 *
 * @property int $id
 * @property int|null $fk_office_id
 * @property int|null $fk_mgrfr_id
 * @property string|null $reporting_period
 * @property string $created_at
 *
 * @property Mgrfrs $fkMgrfr
 * @property Office $fkOffice
 */
class RapidMgSord extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [

            GenerateIdBehavior::class,
            HistoryLogsBehavior::class
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_rapid_mg_sord';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_office_id', 'fk_mgrfr_id', 'reporting_period'], 'required'],
            ['fk_mgrfr_id', 'validateIfExists'],
            [['id', 'fk_office_id', 'fk_mgrfr_id'], 'integer'],
            [['created_at'], 'safe'],
            [['reporting_period'], 'string', 'max' => 255],
            [['id'], 'unique'],
            [['fk_mgrfr_id'], 'exist', 'skipOnError' => true, 'targetClass' => Mgrfrs::class, 'targetAttribute' => ['fk_mgrfr_id' => 'id']],
            [['fk_office_id'], 'exist', 'skipOnError' => true, 'targetClass' => Office::class, 'targetAttribute' => ['fk_office_id' => 'id']],
        ];
    }
    public function validateIfExists($attribute)
    {
        $buildQry = self::find()
            ->addSelect(['id'])
            ->andWhere(['fk_mgrfr_id' => $this->fk_mgrfr_id])
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
            'fk_office_id' => 'Office',
            'fk_mgrfr_id' => 'Mgrfr',
            'reporting_period' => 'Reporting Period',
            'created_at' => 'Created At',
        ];
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

    /**
     * Gets query for [[FkOffice]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOffice()
    {
        return $this->hasOne(Office::class, ['id' => 'fk_office_id']);
    }
}
