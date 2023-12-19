<?php

use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PropertyArticles */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Property Articles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="property-articles-view card container">


    <p>
        <?= Yii::$app->user->can('update_property_articles') ? Html::a('<i class="fa fa-pencil-alt"></i> Update', ['update', 'id' => $model->id], ['class' => 'mdModal btn btn-primary']) : '' ?>

    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'article_name',
        ],
    ]) ?>

</div>
<style>
    .container {
        padding: 2rem;
    }
</style>
<?php   ?>