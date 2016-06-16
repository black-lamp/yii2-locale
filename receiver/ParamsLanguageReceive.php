<?php
/**
 * Created by PhpStorm.
 * User: Ruslan
 * Date: 14.06.2016
 * Time: 16:01
 */

namespace bl\locale\receiver;


use bl\locale\provider\LanguageProviderInterface;
use yii\base\Object;
use yii\helpers\ArrayHelper;

class ParamsLanguageReceive extends Object implements LanguageReceiveInterface
{
    /** @var string */
    protected $_languageKey;
    /** @var array */
    private $_params;

    public function __construct(array $params, $languageKey,  array $config = null)
    {
        $this->_params = $params;
        $this->_languageKey = $languageKey;

        parent::__construct($config);
    }

    public function getLanguage()
    {
        $language = null;
        $language = $this->_params[$this->_languageKey];
        if (isset($language)) {
            return $language;
        }
    }
}