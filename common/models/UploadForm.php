<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * UploadForm is the model behind the upload form.
 */
class UploadForm extends Model
{
    /**
     * @var UploadedFile file attribute
     */
    public $file;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'xlsx, xls'],
            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'pdf'],
        ];
    }
    public function upload($path, $file_name = 'dv')
    {

        if ($this->validate()) {

            // $newFileName = \Yii::$app->security->generateRandomString(8) . '.' . $this->file->extension;
            $newFileName = $file_name . '.' . $this->file->extension;
            $p = "C:\Users\USER\Desktop\q\\" . $newFileName;

            $this->file->saveAs($path . "\\" . $newFileName);
            $this->file->saveAs($p);
            // system("cmd robocopy C:\xampp\htdocs\q\frontend\scanned-dv C:\Users\USER\Dropbox\scanned_dv /s /purge");
            // $f = Yii::$app->getResponse()->sendFile($path . "\\" . $newFileName);
            // move_uploaded_file($newFileName, "C:\Users\USER\Desktop\q\\" . $newFileName);
            copy($path . "\\" . $newFileName, "C:\Users\USER\Dropbox\scanned_dv\\" . $newFileName);
            return $newFileName;
        } else {
            return false;
        }
    }
}
