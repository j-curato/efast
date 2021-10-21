<?php

use kartik\grid\GridView;
use yii\data\ActiveDataProvider;


$this->title = 'Rao';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container panel panel-default">

    <video id="preview"></video>

    <input type="text" id="sample_text">



</div>
<style>
    .grid-view td {
        white-space: normal;
        width: 100px;
    }
</style>
<script>
    let scanner = new Instascan.Scanner({
        video: document.getElementById('preview')
    });
    scanner.addListener('scan', function(content) {
        console.log(content);
    });
    Instascan.Camera.getCameras().then(function(cameras) {
        if (cameras.length > 0) {
            scanner.start(cameras[0]);
        } else {
            console.error('No cameras found.');
        }
    }).catch(function(e) {
        console.error(e);
    });
    scanner.addListener('scan', (c) => {
        console.log(c)

        $('#sample_text').val(c)
    })
</script>