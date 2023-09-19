<?php

use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\SsfSpStatus */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Ssf Sp Statuses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="ssf-sp-status-view container card">


    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary modalButtonUpdate']) ?>

    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
        ],
    ]) ?>

</div>
<?php
$this->registerJsFile('@web/frontend/web/js/globalFunctions.js', ['depends' => JqueryAsset::class]);

?>
<style>
    .container {
        padding: 3rem;
    }
</style>