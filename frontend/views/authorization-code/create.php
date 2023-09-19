<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AuthorizationCode */

$this->title = 'Create Authorization Code';
$this->params['breadcrumbs'][] = ['label' => 'Authorization Codes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="authorization-code-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
