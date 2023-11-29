<?php

use aryelds\sweetalert\SweetAlertAsset;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\FmiTranches */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Fmi Tranches', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
SweetAlertAsset::register($this);
?>
<div class="fmi-tranches-view" id="main">
    <div class="container">
        <div class="card p-2">
            <span>
                <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary mdModal']) ?>
            </span>
        </div>
        <div class="card p-3">

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'tranche_number',
                ],
            ]) ?>
        </div>
    </div>



</div>