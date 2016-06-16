<?php
namespace bl\locale;

use bl\locale\provider\LanguageProviderInterface;
use bl\locale\receiver\CookieLanguageReceive;
use bl\locale\receiver\ParamsLanguageReceive;
use bl\locale\receiver\ReceiveContainer;
use bl\locale\receiver\SessionLanguageReceive;
use bl\locale\receiver\UrlLanguageReceive;
use bl\locale\saver\CookieLanguageSave;
use bl\locale\saver\SaveConteiner;
use bl\locale\saver\SessionLanguageSave;
use yii\base\InvalidValueException;
use yii\helpers\ArrayHelper;
use yii\web\Cookie;
use yii\web\UrlRule;
use \yii\web\UrlManager as BaseUrlManager;

/**
 * Created by PhpStorm.
 * User: Ruslan
 * Date: 12.06.2016
 * Time: 15:46
 */
class UrlManager extends BaseUrlManager
{
    public $languageKey = 'lang';
    public $sessionLanguageKey = '_lang';
    public $cookieLanguageKey = '_lang';

    public $detectInSession = false;
    public $detectInCookie = false;

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
        \Yii::$container->set('bl\locale\provider\LanguageProviderInterface', $this->languageProvider);
        \Yii::$container->set('languageProvider', 'bl\locale\provider\LanguageProviderInterface');
    }

    public function parseRequest($request)
    {
        /** @var LanguageProviderInterface $languagePovider */
        $languagePovider = \Yii::$container->get('languageProvider');
        $languages = $languagePovider->getLanguages();
        $language = &\Yii::$app->language;
        if (empty($languages)) {
            throw new InvalidValueException('languages not set');
        }
        $languagePattern = implode('|', ArrayHelper::merge(array_keys($languages), array_filter(array_values($languages))));

        $receive = new ReceiveContainer();

        if ($this->detectInSession) {
            $receive->addReceiver(new SessionLanguageReceive($this->sessionLanguageKey));
        }
        if ($this->detectInCookie) {
            $receive->addReceiver(new CookieLanguageReceive($this->cookieLanguageKey));
        }
        $languageFromStorage = $receive->getLanguage();

        $mathed = preg_match("~^(?<language>(?:$languagePattern)?)/?(?<url>.*)~i", $request->getPathInfo(), $mathes);

        $request->setPathInfo($mathed > 0 ? $mathes['url'] : $request->getPathInfo());
        $this->language = !empty($mathes['language']) && !$this->showDefault ? $mathes['language'] : $languageFromStorage;
        $language = $this->language;

        $saver = new SaveConteiner();
        if ($this->detectInSession) {
            $saver->add(new SessionLanguageSave($this->sessionLanguageKey));
        }
        if ($this->detectInCookie) {
            $saver->add(new CookieLanguageSave($this->cookieLanguageKey));
        }
        $saver->save($language);
//        if ($this->detectInCookie) {
//            \Yii::$app->response->cookies->add(new Cookie([
//                'name' => $this->cookieLanguageKey,
//                'value' => $language,
//            ]));
//        }
//
//        if ($this->detectInSession) {
//
//            if (!\Yii::$app->session->isActive) {
//                \Yii::$app->session->open();
//                \Yii::$app->session->set($this->sessionLanguageKey, $language);
//                \Yii::$app->session->close();
//            } else {
//                \Yii::$app->session->set($this->sessionLanguageKey, $language);
//            }
//        }
        $test = parent::parseRequest($request);
        return $test;
    }

    public function createUrl($params)
    {
        $receive = new ReceiveContainer();

        $receive->addReceiver(new ParamsLanguageReceive($params, $this->languageKey));

        if ($this->detectInSession) {
            $receive->addReceiver(new SessionLanguageReceive($this->sessionLanguageKey));
        }

        if ($this->detectInCookie) {
            $receive->addReceiver(new CookieLanguageReceive($this->cookieLanguageKey));
        }

        $language = $receive->getLanguage();

//        var_dump($language);
//        if (empty($language)) {
//            $language = \Yii::$app->language;
//        }
        unset($params[$this->languageKey]);

        if (!isset($language)) {
            $language = \Yii::$app->language;
        }

//        $language = isset($language) ? $language : $this->language;
        $language = $this->lowerCase ? strtolower($language) : $language;
        $language = $this->useShortSyntax ? preg_replace('~(\w{2})-\w{2}~i', '$1', $language, 1) : $language;

        return $this->showDefault || strcasecmp($language, $this->defaultLanguage) != 0
            ? substr_replace(parent::createUrl($params), !empty($language) ? "/$language" : '', strlen($this->baseUrl), 0)
            : parent::createUrl($params);
    }

}