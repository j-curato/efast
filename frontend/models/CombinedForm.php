<?php

namespace app\models;

use yii\base\Model;

class CombinedForm extends Model
{
    public $model1;
    public $model2;

    public function rules()
    {
        return [
            [['model1', 'model2'], 'safe'], // Add validation rules if needed
        ];
    }
}
