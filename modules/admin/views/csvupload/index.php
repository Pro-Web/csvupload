<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CsvUpload */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'CsvUpload';
$this->params['breadcrumbs'][] = $this->title;

$script = <<< JS

    var $ = jQuery;
    $(".upload-csv").click( function(){ 

        $("#get-file-csv").trigger('click'); 

        $("#get-file-csv").change( function(){

        var data_file = new FormData();
        data_file.append( 'action_name', 'get_upload' );
        data_file.append( 'csvFile', $(this).prop('files')[0] );

        $('.uploads').css('display', 'none');
        $('.procces-upload').css('display', 'block');
        
        $.ajax({
            type: 'POST',
            url: '/admin/csvupload/fileupload',
            data: data_file,
            processData: false, // Не обрабатываем файлы (Don't process the files)
            contentType: false,
        error: function(error){
            alert('Что то пошло не так ! Попробуйте еще раз.');
        },
        success: function(respond, textStatus, jqXHR){
            
            //window.location.reload();
            
            $('.procces-upload').css('display', 'none');
            $('.response').text(respond);

            setTimeout(function(){

                $('.uploads').css('display', 'block');

                $('.response').text('');

            },3000);

        }

    } );  } );
    
    });


JS;

$this->registerJs($script );


?>
<div class="category-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p class="uploads">
        <?= Html::a('Create Stroka', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::button('Upload CsvFile', ['class' => 'btn btn-success upload-csv']) ?>
        <input type="file" id="get-file-csv" name="csvFile" accept=".csv" style="display: none">
    </p>
    <div class="procces-upload" style="display: none">

             <img src="/img/loadstyl.gif" style="width: 50px; margin-right: 15px;">
             <span>Пожалуйста подождите ! Идет загрузка данныx...</span>
            
    </div>

    <span class="response" style="color: green;"></span>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'column',
            'title',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
