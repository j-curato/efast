<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\DocumentReceive */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Document Receives', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="document-recieve-view">

    <div class="container card" style="padding: 2rem;">

        <p>
            <?= Yii::$app->user->can('update_document_receive') ? Html::a('<i class="fa fa-pencil-alt"></i> Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary modalButtonUpdate']) : '' ?>

        </p>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'name',
                'description',
            ],
        ]) ?>
    </div>

</div>
<?php $this->registerJsfile('@web/frontend/web/js/globalFunctions.js', ['depends' => [\yii\web\JqueryAsset::class]]) ?>