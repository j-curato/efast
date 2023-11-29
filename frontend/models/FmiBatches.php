<?php

namespace app\models;

use Yii;
use yii\db\Expression;
use app\behaviors\GenerateIdBehavior;

/**
 * This is the model class for table "tbl_fmi_batches".
 *
 * @property int $id
 * @property string $batch_name
 *
 * @property FmiSubprojects[] $tblFmiSubprojects
 */
class FmiBatches extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            'idGenerator' => [
                'class' => GenerateIdBehavior::class
            ]
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_fmi_batches';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['batch_name'], 'required'],
            [['id'], 'integer'],
            [['batch_name'], 'string'],
            [['id'], 'unique'],
            [['created_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'batch_name' => 'Batch Name',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[FmiSubprojects]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFmiSubprojects()
    {
        return $this->hasMany(FmiSubprojects::class, ['fk_fmi_batch_id' => 'id']);
    }
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                if (empty($this->id)) {
                    $this->id = $this->generateId();
                }
            }
            return true;
        }
        return false;
    }

    public static function searchBatch($page = null, $text = null, $id = null)
    {
        $limit = 5;
        $offset = ($page - 1) * $limit;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => self::findOne($id)->serial_number];
        } else if (!is_null($text)) {
            $query = self::find()
                ->addSelect([
                    new Expression("CAST(id AS CHAR(50)) as id"),
                    new Expression("batch_name as text"),
                ])
                ->where(['like', 'tbl_fmi_batches.batch_name', $text])
                ->offset($offset)
                ->limit($limit);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
            $out['pagination'] = ['more' => !empty($data) ? true : false];
        }
        return $out;
    }
}
