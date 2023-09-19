<?php

use app\models\SubAccounts1;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap4\Modal;
use kartik\widgets\ActiveForm;
use kartik\widgets\FileInput;
use aryelds\sweetalert\SweetAlertAsset;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TransactionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Transactions';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="transaction-index">


    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

    <?= $form->field($model, 'file')->fileInput() ?>

    <button>Submit</button>

    <?php ActiveForm::end() ?>





</div>
<style>
    .grid-view td {
        white-space: normal;
        width: 5rem;
        padding: 0;
    }
</style>