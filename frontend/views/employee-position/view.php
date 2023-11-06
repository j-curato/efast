<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\EmployeePosition */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Employee Positions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="employee-position-view">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::button('<i class="glyphicon glyphicon-pencil"></i> Update', ['value' => Url::to(yii::$app->request->baseUrl . '/index.php?r=employee-position/update&id=' . $model->id), 'id' => 'mdModal', 'class' => 'btn btn-success', 'data-placement' => 'left', 'data-toggle' => 'tooltip', 'title' => 'Add Sector']); ?>

        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="container">

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'position',
            ],
        ]) ?>
    </div>

</div>
<style>
    .container{
        background-color: white;
        padding: 3rem;
    }
</style>