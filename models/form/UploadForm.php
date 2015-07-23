<?php
use yii\base\Model;

/**
 * Created by PhpStorm.
 * User: vilison
 * Date: 2015/5/2
 * Time: 23:31
 */
class UploadForm extends Model
{
    /**
     * @var UploadedFile|Null file attribute
     */
    public $file;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['file'], 'file'],
        ];
    }
}