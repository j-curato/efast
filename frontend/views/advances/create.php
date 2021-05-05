<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Advances */

$this->title = 'Create Advances';
$this->params['breadcrumbs'][] = ['label' => 'Advances', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="advances-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form_new', [
        'model' => $model,
    ]) ?>

</div>
