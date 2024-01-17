<?php

namespace app\behaviors;

use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;

class GenerateIdBehavior extends Behavior
{
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'generateId',
        ];
    }

    public function generateId()
    {

        if ($this->owner->isNewRecord) {
            $this->owner->id = Yii::$app->db->createCommand('SELECT UUID_SHORT() % 9223372036854775807')->queryScalar();
        }
    }
}
