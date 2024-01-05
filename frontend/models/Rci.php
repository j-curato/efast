<?php

namespace app\models;

use Yii;
use DateTime;
use yii\db\Expression;
use app\behaviors\GenerateIdBehavior;
use app\behaviors\HistoryLogsBehavior;

/**
 * This is the model class for table "rci".
 *
 * @property int $id
 * @property string $serial_number
 * @property int $fk_book_id
 * @property string $date
 * @property string $reporting_period
 * @property string $created_at
 *
 * @property Books $fkBook
 * @property RciItems[] $rciItems
 */
class Rci extends \yii\db\ActiveRecord
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
        return 'rci';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_book_id', 'date', 'reporting_period'], 'required'],
            [['id', 'fk_book_id'], 'integer'],
            [['date', 'created_at'], 'safe'],
            [['serial_number'], 'string', 'max' => 255],
            [['reporting_period'], 'string', 'max' => 10],
            [['serial_number'], 'unique'],
            [['id'], 'unique'],
            [['fk_book_id'], 'exist', 'skipOnError' => true, 'targetClass' => Books::class, 'targetAttribute' => ['fk_book_id' => 'id']],
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
            'fk_book_id' => ' Book ',
            'date' => 'Date',
            'reporting_period' => 'Reporting Period',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[FkBook]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBook()
    {
        return $this->hasOne(Books::class, ['id' => 'fk_book_id']);
    }

    /**
     * Gets query for [[RciItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRciItems()
    {
        return $this->hasMany(RciItems::class, ['fk_rci_id' => 'id']);
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
        $yr = DateTime::createFromFormat('Y-m', $this->reporting_period)->format('Y');
        $qry  = self::find()
            ->addSelect([
                new Expression("CAST(SUBSTRING_INDEX(serial_number,'-',-1)AS UNSIGNED)  as last_num")
            ])
            ->andWhere("rci.serial_number LIKE :yr", ['yr' => $yr . "%"])
            ->orderBy('last_num DESC')
            ->limit(1)
            ->scalar();

        $num = !empty($qry) ? intval($qry) + 1 : 1;
        return $this->reporting_period . '-' . str_pad($num, 4, '0', STR_PAD_LEFT);
    }
}
