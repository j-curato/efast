<?php

use kartik\icons\Icon;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Collapse;
use yii\captcha\Captcha;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $changePassModel \common\models\LoginForm */

$this->title = 'Profile';

$fieldOptions1 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>"
];

$fieldOptions2 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];


?>
<div class="card">

    <div class="  ">

        <?= Collapse::widget([

            'items' => [
                [
                    'label' => 'Change Password ',
                    'content' => $this->render('change_password', ['model' => $changePassModel]),
                    'headerOptions' => ['class' => 'card'],
                    'contentOptions' => ['class' => Yii::$app->session->hasFlash('error-change-pass') ? 'in' : '']
                ],

            ],
        ]) ?>
    </div>
    <!-- <div class=" panel ">

        <?= Collapse::widget([

            'items' => [
                [
                    'label' => 'Create Account',
                    'content' => $this->render('signup', ['model' => $createAcc]),
                    'headerOptions' => ['class' => 'card'],
                    'contentOptions' => ['class' => Yii::$app->session->hasFlash('error-change-pass') ? 'in' : '']
                ],

            ],
        ]) ?>
    </div> -->

</div>

<style>

</style>