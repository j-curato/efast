<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Mgrfrs */

$this->title = 'Create MG RFRs';
$this->params['breadcrumbs'][] = ['label' => 'MG RFRs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mgrfrs-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
