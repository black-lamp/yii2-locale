<?php


class CreateUrlTest extends bl\locale\tests\unit\TestCase
{

    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {

    }

    protected function _after()
    {
    }


    /**
     * Test create Url
     */
    public function testCreateUrl()
    {

        $mockApp = $this->app;
        /** @var \bl\locale\UrlManager $urlManager */
        $urlManager = $this->app->urlManager;

        $url = 'site/index';

        $actual = $urlManager->createUrl([$url]);
        $expected = implode('/', ['', $mockApp->language, $url]);

        \Codeception\Util\Debug::debug($actual);

        $this->tester->assertEquals($expected, $actual);
    }


    /**
     * Test create absolute Url
     * @throws \yii\base\InvalidConfigException
     */
    public function testCreateAbsoluteUrl()
    {

        $mockApp = $this->app;
        /** @var \bl\locale\UrlManager $urlManager */
        $urlManager = $this->app->urlManager;

        $url = 'site/index';

        $actual = $urlManager->createAbsoluteUrl([$url]);
        $expected = implode('/', [$urlManager->getHostInfo(), $mockApp->language, $url]);

        \Codeception\Util\Debug::debug($actual);

        $this->tester->assertEquals($expected, $actual);
    }

    /**
     * Test create url with base path
     * @throws \yii\base\InvalidConfigException
     */
    public function testCrateUrlWithBaseUrl()
    {

        $mockApp = $this->app;
        /** @var \bl\locale\UrlManager $urlManager */
        $urlManager = clone $mockApp->urlManager;

        $url = 'site/index';
        $baseUrl = '/admin';

        $urlManager->baseUrl = $baseUrl;

        $urlActual = $urlManager->createUrl($url);
        $urlExpected = $expected = implode('/', [$baseUrl, $mockApp->language, $url]);;

        \Codeception\Util\Debug::debug("Url: $urlActual");
        $this->tester->assertEquals($urlExpected, $urlActual);


        $absoluteUrlActual = $urlManager->createAbsoluteUrl($url);
        $absoluteUrlExpected = implode('/', [$urlManager->getHostInfo() . $baseUrl, $mockApp->language, $url]);

        \Codeception\Util\Debug::debug("Absolute Url: $absoluteUrlActual");
        $this->tester->assertEquals($absoluteUrlExpected, $absoluteUrlActual);
    }

    public function testHideDefaoultLanguage()
    {

        $mockApp = $this->app;
        /** @var \bl\locale\UrlManager $urlManager */
        $urlManager = clone $mockApp->urlManager;

        $urlManager->showDefault = false;
        $url = 'site/index';

        \Codeception\Util\Debug::debug("Default language: {$mockApp->sourceLanguage}");

        $actual = $urlManager->createUrl([$url, $urlManager->languageKey => 'en-US']);
        $expected = implode('/', ['', $url]);

        \Codeception\Util\Debug::debug("Hiden default language: $actual");

        $this->tester->assertEquals($expected, $actual);

        $language = 'ru-RU';
        $actual = $urlManager->createUrl([$url, $urlManager->languageKey => $language]);
        $expected = implode('/', ['', $language, $url]);

        \Codeception\Util\Debug::debug("Change language: $actual");

        $this->tester->assertEquals($actual, $expected);

    }
}