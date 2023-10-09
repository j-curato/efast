<?php

namespace app\models;

use ErrorException;
use Yii;

/**
 * This is the model class for table "notice_of_postponement".
 *
 * @property int $id
 * @property string $serial_number
 * @property string $created_at
 *
 * @property NoticeOfPostponementItems[] $noticeOfPostponementItems
 */
class NoticeOfPostponement extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notice_of_postponement';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['created_at'], 'safe'],
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
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[NoticeOfPostponementItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNoticeOfPostponementItems()
    {
        return $this->hasMany(NoticeOfPostponementItems::class, ['fk_notice_of_postponement_id' => 'id']);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                if (empty($this->id)) {
                    $this->id = Yii::$app->db->createCommand("SELECT UUID_SHORT() % 9223372036854775807")->queryScalar();
                }
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
        $query = Yii::$app->db->createCommand("SELECT CAST(SUBSTRING_INDEX(serial_number,'-',-1)  AS UNSIGNED) as last_number
        FROM notice_of_postponement
        ORDER BY last_number DESC LIMIT 1")
            ->queryScalar();
        $nxtNum = !empty($query) ? intval($query) + 1 : 1;
        return  date('Y') . '-' .  str_pad($nxtNum, 4, '0', STR_PAD_LEFT);
    }
    public function insertItems($items)
    {
        try {
            if (empty($items)) {
                throw new ErrorException('Insert Items');
            }
            $sql = '';
            $params = [];
            $ids = array_column($items, 'id');

            if (!empty($ids)) {
                $sql  = ' AND ';
                $sql .= Yii::$app->db->queryBuilder->buildCondition(['NOT IN', 'id', $ids], $params);
            }
            Yii::$app->db->createCommand("UPDATE 
                SET .is_deleted = 1 
                WHERE .record_allotment_id = :id
                AND .is_deleted= 0
                $sql", $params)
                ->bindValue(':id', $this->id)
                ->execute();
            $itemModels = [];
            foreach ($items as $item) {
                $itemModel = !empty($item['id']) ? NoticeOfPostponementItems::findOne($item['id']) : new NoticeOfPostponementItems();
                $itemModel->attributes = $item;
                $itemModel->fk_notice_of_postponement_id = $this->id;
                $itemModel->is_deleted = 0;
                $itemModels[] = $itemModel;
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
    public function getItemsA()
    {

        return Yii::$app->db->createCommand("SELECT 
            notice_of_postponement_items.id as item_id,
            notice_of_postponement_items.from_date,
            notice_of_postponement_items.to_date,
            notice_of_postponement_items.fk_rfq_id,
            pr_rfq.rfq_number
            FROM notice_of_postponement_items
            JOIN pr_rfq ON notice_of_postponement_items.fk_rfq_id = pr_rfq.id
            WHERE 
            notice_of_postponement_items.fk_notice_of_postponement_id = 100516645214093401
            AND
            notice_of_postponement_items.is_deleted = 0")
            ->bindValue(':id', $this->id)
            ->queryAll();
    }
}
