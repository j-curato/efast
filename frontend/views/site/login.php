<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'Sign In';

$fieldOptions1 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>"
];

$fieldOptions2 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];
?>


<div class="login-box panel panel-default">
    <div class="login-logo">
        <a href="#"><b>DTI</b> System</a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">

        <?php $form = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => true, 'options' => ['autocomplete' => 'off']]); ?>

        <?= $form
            ->field($model, 'username', $fieldOptions1)
            ->label(false)
            ->textInput(['placeholder' => $model->getAttributeLabel('username'), 'options' => ['autocomplete' => 'off']]) ?>

        <?= $form
            ->field($model, 'password', $fieldOptions2)
            ->label(false)
            ->passwordInput(['placeholder' => $model->getAttributeLabel('password'), 'options' => ['autocomplete' => 'off']]) ?>
        <?= $form->field($model, 'captcha')->widget(Captcha::class, [
            'template' => '<div class="row">
            <div class="col-lg-3">{image}</div>
            <div class="col-sm-1"></div>
            <div class="col-lg-6">{input}</div>
        </div>',
        ]) ?>
        <div class="row">
            <div class="col-xs-8">
                <?= $form->field($model, 'rememberMe')->checkbox() ?>
            </div>
            <!-- /.col -->
            <div class="col-xs-4">
                <?= Html::submitButton('Sign in', ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'login-button']) ?>
            </div>
            <!-- /.col -->
        </div>


        <?php ActiveForm::end(); ?>
        <!-- 
        <div class="social-auth-links text-center">
            <p>- OR -</p>
            <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in
                using Facebook</a>
            <a href="#" class="btn btn-block btn-social btn-google-plus btn-flat"><i class="fa fa-google-plus"></i> Sign
                in using Google+</a>
        </div> -->
        <!-- /.social-auth-links -->

        <!-- <a href="#">I forgot my password</a><br> -->
        <!-- <a href="index.php?r=site/signup" class="text-center">Register a new membership</a> -->

    </div>
    <!-- /.login-box-body -->
</div><!-- /.login-box -->


<style>
    .login-page {
        background-color: #E4EAF3;
    }

    .login-box,
    .register-box {
        width: 400px;
        padding: 1em;
        margin: 15% auto;
    }
</style>