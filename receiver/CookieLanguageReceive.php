<?php

namespace bl\locale\receiver;


use bl\locale\provider\LanguageProviderInterface;
use yii\base\BaseObject;

class CookieLanguageReceive extends BaseObject implements LanguageReceiveInterface
{
    protected $_languageKey;

    public function __construct($languageKey, array $config = null)
    {
        $this->_languageKey = $languageKey;
        parent::__construct($config);
    }


    public function getLanguage()
    {
        $language = null;
        $key = $this->_languageKey;
        
        if (\Yii::$app->request->cookies->has($key)) {
            $language = \Yii::$app->request->cookies->get($key)->value;
        }
        if (isset($language)) {
            return $language;
        }
    }
}