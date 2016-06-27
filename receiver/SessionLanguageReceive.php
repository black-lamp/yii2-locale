<?php
/**
 * Created by PhpStorm.
 * User: Ruslan
 * Date: 14.06.2016
 * Time: 15:34
 */

namespace bl\locale\receiver;


use bl\locale\provider\LanguageProviderInterface;
use yii\base\Object;

class SessionLanguageReceive extends Object implements LanguageReceiveInterface
{
    protected $_languageKey;

    /**
     * SessionLanguageReceive constructor.
     * @param string $languageKey
     * @param array|null $config
     */
    public function __construct($languageKey, array $config = null)
    {
        $this->_languageKey = $languageKey;
        parent::__construct($config);
    }
    
    public function getLanguage()
    {
        $language = null;

        $language = \Yii::$app->session->get($this->_languageKey);

        if (isset($language)) {
            return $language;
        }
    }
}