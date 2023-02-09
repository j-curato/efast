<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use aryelds\sweetalert\SweetAlertAsset;

/* @var $this yii\web\View */
/* @var $model app\models\SubAccounts1 */

$this->title = $model->object_code;
$this->params['breadcrumbs'][] = ['label' => 'Sub Accounts1s', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="sub-accounts1-view">





    <div class="container panel panel-default" style="padding: 2rem;">

        <p>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary modalButtonCreate']) ?>
            <?= Html::a('<i class="fa fa-plus"></i> Create Sub Account 2', ['sub-accounts2/create', 'subAcc1Id' => $model->id], ['class' => 'btn btn-success modalButtonCreate']) ?>

        </p>
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'chart_of_account_id',
                'object_code',
                'name',
            ],
        ]) ?>
    </div>

</div>
<?php $this->registerJsFile('@web/frontend/web/js/globalFunctions.js', ['depends' => [\yii\web\JqueryAsset::class]]) ?>