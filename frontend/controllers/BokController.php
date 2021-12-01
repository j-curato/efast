<?php

namespace frontend\controllers;

use common\models\Books;

class BokController extends \yii\rest\ActiveController
{

    public $modelClass = Books::class;
}
