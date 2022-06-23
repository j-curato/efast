<?php

use app\models\ResponsibilityCenter;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $model app\models\JevPreparation */

$this->title = 'Create Jev Preparation';
$this->params['breadcrumbs'][] = ['label' => 'Jev Preparations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jev-preparation-create">

    <?= $this->render('_form_new', [
        'model' => $model,
        'type' => $type,
        'entries' => $entries,
    ]) ?>
</div>

<?php
// $this->registerJs($js, $this::POS_END);
?>