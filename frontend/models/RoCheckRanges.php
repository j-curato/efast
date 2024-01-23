<?php

namespace app\models;

use Yii;
use app\behaviors\HistoryLogsBehavior;

/**
 * This is the model class for table "ro_check_range".
 *
 * @property int $id
 * @property int $fk_book_id
 * @property int $from
 * @property int $to
 * @property string $created_at
 *
 * @property Books $fkBook
 */
class RoCheckRanges extends \yii\db\ActiveRecord
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
        return 'ro_check_ranges';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_book_id', 'from', 'to', 'check_type'], 'required'],
            [['fk_book_id', 'from', 'to', 'check_type'], 'integer'],
            [['created_at'], 'safe'],
            [['fk_book_id'], 'exist', 'skipOnError' => true, 'targetClass' => Books::class, 'targetAttribute' => ['fk_book_id' => 'id']],
        ];
    }
    // public function ValidateTo($attribute, $params)
    // {

    //     if ($this->to < $this->from) {
    //         $this->addError('to', 'Your salary is not enough for children.');
    //     }
    // }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_book_id' => 'Book ',
            'from' => 'From Check No.',
            'to' => 'To Check No.',
            'check_type' => 'Check Type',
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
    public function getNextCheckNumber()
    {
        return Yii::$app->db->createCommand("WITH RECURSIVE CheckNumberSequence AS (
            SELECT  ro_check_ranges.`from` as num,ro_check_ranges.`to` FROM ro_check_ranges WHERE id = :id
            UNION
            SELECT num + 1,
                CheckNumberSequence.`to`
            FROM CheckNumberSequence
                WHERE num <  CheckNumberSequence.`to`
        )
        SELECT CheckNumberSequence.num FROM CheckNumberSequence
        WHERE NOT EXISTS (SELECT cash_disbursement.check_or_ada_no FROM cash_disbursement WHERE cash_disbursement.check_or_ada_no =  CheckNumberSequence.num)
        ORDER BY CheckNumberSequence.num LIMIT 1")
            ->bindValue(':id', $this->id)
            ->queryScalar();
    }
}
