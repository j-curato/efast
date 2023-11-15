<?php

namespace app\behaviors;

use app\components\helpers\MyHelper;
use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;

class HistoryLogsBehavior extends Behavior
{
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_UPDATE => 'logChanges',
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
}
