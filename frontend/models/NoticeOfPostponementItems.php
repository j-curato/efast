<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "notice_of_postponement_items".
 *
 * @property int $id
 * @property int $fk_rfq_id
 * @property int $fk_notice_of_postponement_id
 * @property int|null $is_deleted
 * @property string $from_date
 * @property string $to_date
 * @property string $created_at
 *
 * @property NoticeOfPostponement $fkNoticeOfPostponement
 * @property PrRfq $fkRfq
 */
class NoticeOfPostponementItems extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notice_of_postponement_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_rfq_id', 'fk_notice_of_postponement_id', 'from_date',], 'required'],
            [['id',  'fk_notice_of_postponement_id', 'is_deleted'], 'integer'],
            [['from_date', 'to_date', 'created_at'], 'safe'],
            [['id'], 'unique'],
            [['fk_notice_of_postponement_id'], 'exist', 'skipOnError' => true, 'targetClass' => NoticeOfPostponement::class, 'targetAttribute' => ['fk_notice_of_postponement_id' => 'id']],
            [['fk_rfq_id'], 'exist', 'skipOnError' => true, 'targetClass' => PrRfq::class, 'targetAttribute' => ['fk_rfq_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_rfq_id' => 'Fk Rfq ID',
            'fk_notice_of_postponement_id' => 'Fk Notice Of Postponement ID',
            'is_deleted' => 'Is Deleted',
            'from_date' => 'From Date',
            'to_date' => 'To Date',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[FkNoticeOfPostponement]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkNoticeOfPostponement()
    {
        return $this->hasOne(NoticeOfPostponement::class, ['id' => 'fk_notice_of_postponement_id']);
    }

    /**
     * Gets query for [[FkRfq]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRfq()
    {
        return $this->hasOne(PrRfq::class, ['id' => 'fk_rfq_id']);
    }
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                if (empty($this->id)) {
                    $this->id = Yii::$app->db->createCommand("SELECT UUID_SHORT() % 9223372036854775807")->queryScalar();
                }
            }
            return true;
        }
        return false;
    }
}
