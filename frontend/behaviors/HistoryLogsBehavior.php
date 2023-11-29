<?php

namespace app\behaviors;

use Yii;
use yii\base\Behavior;
use yii\db\Expression;
use yii\db\ActiveRecord;
use app\components\helpers\MyHelper;

class HistoryLogsBehavior extends Behavior
{
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_UPDATE => 'logChanges',
            ActiveRecord::EVENT_AFTER_INSERT => 'logCreations',
        ];
    }

    public function logChanges($event)
    {
        $changedAttributes = $this->owner->getDirtyAttributes();
        foreach ($changedAttributes as $attribute => $newValue) {
            $oldValue = $this->owner->getOldAttribute($attribute);
            if ($newValue != $oldValue) {
                // Save the change to the history table
                Yii::$app->db->createCommand()->insert('history_logs', [
                    'id' => MyHelper::getUuid(),
                    'server_name' =>  Yii::$app->request->serverName,
                    'table_name' => $this->owner->tableName(),
                    'row_id' => $this->owner->id,
                    'attribute_name' => $attribute,
                    'old_value' => $oldValue ?? '',
                    'new_value' => $newValue ?? '',
                    'fk_changed_by' => Yii::$app->user->id,
                ])->execute();
            }
        }
    }
    public function logCreations($event)
    {
        $currentTimestamp = new Expression('NOW()');
        Yii::$app->db->createCommand()->insert('tbl_creation_history', [
            'server_name' =>  Yii::$app->request->serverName,
            'table_name' => $this->owner->tableName(),
            'row_id' => $this->owner->id,
            'fk_created_by' => Yii::$app->user->id,
            'created_at' => $currentTimestamp
        ])->execute();
    }
}
