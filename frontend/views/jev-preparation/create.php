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

    <?php

    $dv_entries = '';
    if (!empty($dv_accounting_entries)) {
        $dv_entries  = $dv_accounting_entries;
    }
    $dv_data = '';
    if (!empty($dv)) {
        $dv_data  = $dv;
    }
    ?>
    <?= $this->render('_form', [
        'model' => $model,
        'type' => $type,
        // 'modelJevItems' => $modelJevItems
        'dv_entries' => $dv_entries,
        'dv_data' => $dv_data
    ]) ?>
</div>

<?php
// $this->registerJs($js, $this::POS_END);
?>