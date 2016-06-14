<?php
namespace bl\locale;

use bl\locale\provider\LanguageProviderInterface;
use yii\helpers\ArrayHelper;
use yii\web\Cookie;
use yii\web\UrlRule;

/**
 * Created by PhpStorm.
 * User: Ruslan
 * Date: 12.06.2016
 * Time: 15:46
 */
class UrlManager extends \yii\web\UrlManager
{
    public $languageKey = 'lang';
    public $sessionLanguageKey = '_lang';
    public $cookieLanguageKey = '_lang';

    public $detectInSession = true;
    public $detectInCookie = true;

    public $languages = [];
    
    public $showDefault = true;
    public $useShortSyntax = false;
    public $lowerCase = false;
    public $usePreferredLanguage = false;


    public $language;

    /**
     * ```php
     * 'language' => [
     *    'provider' => 'common\components\locale\provider\DbLanguage',
     *    'table' => 'language',
     *    'localeField' => 'locale',
     *    'languageField' => 'lang',
     * ]
     * ```
     * @var array
     */
    public $languageProvider;

    private $defaultLanguage;

    public function init()
    {
        parent::init();
        $this->defaultLanguage = \Yii::$app->sourceLanguage;
        $this->registerDependencies();
    }

    public function registerDependencies()
    {
        \Yii::$container->set('common\components\locale\provider\LanguageProviderInterface', $this->languageProvider);
        \Yii::$container->set('languageProvider', 'common\components\locale\provider\LanguageProviderInterface');
    }

    public function parseRequest($request)
    {
        /** @var LanguageProviderInterface $languageProvider */
        
//        $pattern = implode('|', ArrayHelper::merge(array_keys($languages), array_filter(array_values($languages))));
//        var_dump($languages);
//        die();
//        var_dump('');
//        var_dump('');
//        var_dump($languages);
        $mathed = preg_match('~^(?<language>\w{2}(?:-\w{2})?)/(?<url>.*)~i', $request->getPathInfo(), $mathes);
//        var_dump($mathes);
        $request->setPathInfo($mathed > 0 ? $mathes['url'] : $request->getPathInfo());
        \Yii::$app->language = isset($mathes['language']) ? $mathes['language'] : \Yii::$app->language;
        $this->language = $mathes['language'];
//        var_dump($mathes);
        if ($this->detectInCookie) {

            \Yii::$app->response->cookies->add(new Cookie([
                'name' => $this->cookieLanguageKey,
                'value' => $mathes['language'],
            ]));
        }

        if ($this->detectInSession) {

            if (!\Yii::$app->session->isActive) {
                \Yii::$app->session->open();
                \Yii::$app->session->set($this->sessionLanguageKey, $mathes['language']);
                \Yii::$app->session->close();
            } else {
                \Yii::$app->session->set($this->sessionLanguageKey, $mathes['language']);
            }
        }
        $test = parent::parseRequest($request);
        return $test;
    }

    public function createUrl($params)
    {
        $language = $params[$this->languageKey];
        $language = isset($language) ? $language : $this->language;
        unset($params[$this->languageKey]);
        $language = $this->lowerCase ? strtolower($language) : $language;
        $language = $this->useShortSyntax ? preg_replace('~(\w{2})-\w{2}~i', '$1', $language, 1) : $language;

//        var_dump($lang);
        return $this->showDefault || strcasecmp($language, $this->defaultLanguage) != 0
            ? substr_replace(parent::createUrl($params), !empty($language) ? "/$language" : '', strlen($this->baseUrl), 0)
            : parent::createUrl($params);
    }

}