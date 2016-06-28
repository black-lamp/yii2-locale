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
use yii\base\InvalidConfigException;
use yii\base\InvalidValueException;
use yii\di\Container;
use yii\helpers\ArrayHelper;
use yii\web\Cookie;
use yii\web\NotFoundHttpException;
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

    /** @var Container */
    private $conteiner;

    public function init()
    {
        parent::init();
        $this->defaultLanguage = \Yii::$app->sourceLanguage;
        $this->conteiner = new Container();

        if (!isset($this->languageProvider)) {
            throw new InvalidConfigException('$languageProvider must be set');
        }

        $this->registerDependencies();
    }

    public function registerDependencies()
    {
        $this->conteiner->set('bl\locale\provider\LanguageProviderInterface', $this->languageProvider);
        $this->conteiner->set('languageProvider', 'bl\locale\provider\LanguageProviderInterface');
    }

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function getAllowedLanguages()
    {
        /** @var LanguageProviderInterface $languageProvider */
        $languageProvider = $this->conteiner->get('languageProvider');
        return $languageProvider->getLanguages();
    }

//    public function parseRequest($request)
//    {
//        $allowedLanguages = $this->getAllowedLanguages();
//        $url = $request->getPathInfo();
//        $language = null;
//
//        if (empty($allowedLanguages)) {
//            throw new InvalidConfigException('empty language list');
//        }
//        $pattern = '~^(?<id>(?<language>\w{2}\b)(?:-(?<locale>\w{2}))?)~';
//        $matchCount = preg_match($pattern, $url, $localeMatch);
//        $languageInUrl = $matchCount > 0;
//
//        $allowedLanguages = array_values($allowedLanguages);
//
//        if ($languageInUrl) {
//            $localeId = $localeMatch['id'];
//            $keyExists = array_search(strtolower($localeId), array_map('strtolower', $allowedLanguages));
//
//            if ($keyExists !== false) {
//                \Yii::$app->language = $localeId;
//                $request->setPathInfo(preg_replace("~^$localeId/?~i", '', $url));
//            } else {
//                throw new InvalidConfigException("Language '$localeId' not supported");
//            }
//        }
//
//        $receive = new ReceiveContainer();
//        $saver = new SaveConteiner();
//        if (!$languageInUrl && $this->detectInSession) {
//            $receive->addReceiver(new SessionLanguageReceive($this->sessionLanguageKey));
//            $saver->add(new SessionLanguageSave($this->sessionLanguageKey));
//        } elseif (!$languageInUrl && $this->detectInCookie) {
//            $receive->addReceiver(new CookieLanguageReceive($this->cookieLanguageKey));
//            $saver->add(new CookieLanguageSave($this->cookieLanguageKey));
//        }
//
//        $language = $receive->getLanguage();
//        \Yii::$app->language = (!$languageInUrl && isset($language)) ? $language : \Yii::$app->language;
//        $saver->save(\Yii::$app->language);
//
//        return parent::parseRequest($request);
//    }


    public function parseRequest($request)
    {
        /** @var LanguageProviderInterface $languagePovider */
        $languagePovider = $this->conteiner->get('languageProvider');
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

        //todo: fix mathes
        $mathed = preg_match("~^(?<language>(?:$languagePattern)?)/?(?<url>.*)~i", $request->getPathInfo(), $mathes);

        $request->setPathInfo(isset($mathes['language']) ? $mathes['url'] : $request->getPathInfo());
        $language = !empty($mathes['language']) ? $mathes['language'] : \Yii::$app->sourceLanguage;
//        && !$this->showDefault
//            ? $mathes['language']
//            :
//            (is_null($languageFromStorage)
//                ? $language
//                : $languageFromStorage);
        if (!$this->showDefault && strtolower($mathes['language']) == strtolower(\Yii::$app->sourceLanguage)) {
            throw new NotFoundHttpException(\Yii::t('yii', 'Page not found.'));
        }
        $saver = new SaveConteiner();

        if ($this->detectInSession) {
            $saver->add(new SessionLanguageSave($this->sessionLanguageKey));
        }
        if ($this->detectInCookie) {
            $saver->add(new CookieLanguageSave($this->cookieLanguageKey));
        }
        $saver->save($language);
        return parent::parseRequest($request);
    }

    public function createUrl($params)
    {
        $params = is_string($params) ? [0 => $params] : $params;


        $languagePovider = $this->conteiner->get('languageProvider');
        $languages = $languagePovider->getLanguages();


        $receive = new ReceiveContainer();
        $receive->addReceiver(new ParamsLanguageReceive($params, $this->languageKey));

        if ($this->detectInSession) {
            $receive->addReceiver(new SessionLanguageReceive($this->sessionLanguageKey));
        }

        if ($this->detectInCookie) {
            $receive->addReceiver(new CookieLanguageReceive($this->cookieLanguageKey));
        }
        $language = $receive->getLanguage();


        unset($params[$this->languageKey]);

        if (!isset($language)) {
            $language = \Yii::$app->language;
        }
        $this->language = $language;
//        $language = isset($language) ? $language : $this->language;
        $language = $this->lowerCase ? strtolower($language) : $language;
        $language = $this->useShortSyntax ? preg_replace('~(\w{2})-\w{2}~i', '$1', $language, 1) : $language;

        return $this->showDefault || strcasecmp($language, $this->defaultLanguage) != 0
            ? substr_replace(parent::createUrl($params), !empty($language) ? "/$language" : '', strlen($this->baseUrl), 0)
            : parent::createUrl($params);
    }

}