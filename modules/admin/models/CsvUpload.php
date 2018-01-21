<?php

namespace app\modules\admin\models;

use Yii;
use yii\web\ServerErrorHttpException;

/**
 * This is the model class for table "brk_category".
 *
 * @property integer $id
 * @property string $title
 * @property string $curl
 * @property integer $status
 */
class CsvUpload extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'csv_upload';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['column', 'title'], 'required'],
            [['column', 'title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'column' => 'Number',
            'title' => 'Title',
        ];
    }

    /**
     * @Получаем файл, парсим его построчно, полученные данные пишем в БД
     */
    public function insertUploadScv($data_post)
    {

       $uploaddir = Yii::getAlias('@app/web/uploads/');

       self::deleteAll(); //Удаляем все данные

       session_start();
       $_SESSION['name_csv_file'] = md5($_FILES['csvFile']['tmp_name']).$_FILES['csvFile']['name'];
        
        if (Yii::$app->request->isAjax && $data_post['action_name'] == 'get_upload' ) {

            move_uploaded_file( $_FILES['csvFile']['tmp_name'], $uploaddir . $_SESSION['name_csv_file']);

        }

          $fh = fopen ( $uploaddir . $_SESSION['name_csv_file'], 'r' );
          $info_mass = array();

          while ( ( $info = fgetcsv ($fh, 200, ";") ) !== false )
          {

           $info_mass[] = $info;

          }

          fclose ( $fh ); //Конец;

        //header('Content-type: text/html; charset=utf-8');
        //if(!setlocale(LC_ALL, 'ru_RU.utf8')) setlocale(LC_ALL, 'en_US.utf8');
     

        $info_mass_2 = array_chunk($info_mass, 50); //Разбиваем массив, делаем по 50 элементов


        foreach ($info_mass_2 as $key => $value) {
            
            $rows = [];

            foreach ($value as $key2 => $value_2) {
                     
                     if($value_2[0] != 'column' && $value_2[1] != 'title'){

                         $rows[] = [
                            'column' => $value_2[0],
                            'title' => iconv('CP1251','UTF-8',$value_2[1]),
                         ];

                     }
            }

            //print_r($rows);

            $this->insertTable($rows);

        }

        
        $file_tmp = $uploaddir.$_SESSION['name_csv_file'];
        @unlink($file_tmp);

        unset($_SESSION['name_csv_file']);

    }


    public function insertTable($rows)
    {

        Yii::$app->db->createCommand()->batchInsert(static::tableName(), ['column', 'title'], $rows)->execute();

    }

}
