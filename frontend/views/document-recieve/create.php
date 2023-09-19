<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DocumentReceive */

$this->title = 'Create Document Receive';
$this->params['breadcrumbs'][] = ['label' => 'Document Receives', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="document-recieve-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
