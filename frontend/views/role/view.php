<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Role */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Roles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="role-view">


    <div class="container card">


        <p>
            <?= Html::a('Update', ['update', 'id' => $model->name], ['class' => 'btn btn-primary']) ?>
        </p>

        <table>
            <tr>

                <th>Permissions</th>
            </tr>
            <?php
            foreach ($model->getPermissions() as $item) :
            ?>
                <tr>
                    <td><?= $item->name ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>