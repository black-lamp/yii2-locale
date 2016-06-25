<?php
/**
 * Created by PhpStorm.
 * User: Ruslan
 * Date: 15.06.2016
 * Time: 13:12
 */

namespace bl\locale\saver;


use yii\web\Cookie;

class CookieLanguageSave extends BaseSave implements LanguageSaveInterface
{
    public function save($value)
    {
        $key = $this->_key;
        $isSave = false;

        \Yii::$app->response->cookies->add(new Cookie([
            'name' => $key,
            'value' => $value,
        ]));
        
        $isSave = \Yii::$app->response->cookies->offsetExists($key);
        return $isSave;
    }
}