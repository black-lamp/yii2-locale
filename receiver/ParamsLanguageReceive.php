<?php
/**
 * Created by PhpStorm.
 * User: Ruslan
 * Date: 14.06.2016
 * Time: 16:01
 */

namespace bl\locale\receiver;


use bl\locale\provider\LanguageProviderInterface;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;

class ParamsLanguageReceive extends BaseObject implements LanguageReceiveInterface
{
    /** @var string */
    protected $_languageKey;
    /** @var array */
    private $_params;

    /**
     * ParamsLanguageReceive constructor.
     * @param array|string $params
     * @param string $languageKey
     * @param array|null $config
     */
    public function __construct($params, $languageKey,  array $config = null)
    {
        $this->_params = $params;
        $this->_languageKey = $languageKey;

        parent::__construct($config);
    }

    public function getLanguage()
    {
        $language = null;
        if (key_exists($this->_languageKey, $this->_params)) {
            $language = $this->_params[$this->_languageKey];
        }
        if (isset($language)) {
            return $language;
        }
    }
}