<?php
/**
 * Created by PhpStorm.
 * User: Ruslan
 * Date: 15.06.2016
 * Time: 13:11
 */

namespace bl\locale\saver;


class SessionLanguageSave extends BaseSave implements LanguageSaveInterface
{

    public function save($value)
    {
        $key = $this->_key;
        $isSave = false;
        
        $session = &\Yii::$app->session;
        
        if (!$session->isActive) {
            $session->open();
            $session->set($key, $value);
            $session->close();
        } else {
            $session->set($key, $value);
        }
        
        $isSave = $session->offsetExists($key);
        return $isSave;
    }
}