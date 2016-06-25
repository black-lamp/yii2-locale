<?php


class ParseRequestTest extends bl\locale\tests\unit\TestCase
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

    public function testGetLanguageFromUrl()
    {

    }

    public function testSetLanguageFromCookie()
    {
        $app = \Yii::$app;
        /** @var \bl\locale\UrlManager $urlManager */
        $urlManager = clone $app->urlManager;

        \Codeception\Util\Debug::debug("Before parse request app language: {$app->language}");

        $urlManager->detectInCookie = true;
        $urlManager->detectInSession = false;

        $url = 'site/index';
        $language = 'ru-RU';

        $request = $app->request;

        $request->setPathInfo($url);
        $request->cookies->readOnly = false;
        $request->cookies->add(new \yii\web\Cookie([
            'name' => $urlManager->cookieLanguageKey,
            'value' => $language,
            'expire' => null
        ]));
        $request->cookies->readOnly = true;
        $parse = $urlManager->parseRequest($request);

        \Codeception\Util\Debug::debug("After parse request app language: {$app->language}");

        $this->tester->assertEquals($language, \Yii::$app->language);
        $this->tester->assertEquals($parse, [
            $url, []
        ]);

    }

    public function testSetLanguageFromSession()
    {
        $app = \Yii::$app;
        /** @var \bl\locale\UrlManager $urlManager */
        $urlManager = clone $app->urlManager;

        \Codeception\Util\Debug::debug("Before parse request app language: {$app->language}");
        $urlManager->detectInCookie = false;
        $urlManager->detectInSession = true;


        $url = 'site/index';
        $language = 'ua-UK';
        $app->session->set($urlManager->sessionLanguageKey, $language);

        $request = $app->request;

        $request->setPathInfo($url);
        $parse = $urlManager->parseRequest($request);

        \Codeception\Util\Debug::debug("After parse request app language: {$app->language}");
        $this->tester->assertEquals($language, \Yii::$app->language);
    }

    public function testSetLanguageUrl()
    {
        $app = \Yii::$app;
        /** @var \bl\locale\UrlManager $urlManager */
        $urlManager = clone $app->urlManager;

        \Codeception\Util\Debug::debug("Before parse request app language: {$app->language}");
        $urlManager->detectInCookie = false;
        $urlManager->detectInSession = false;

        $language = 'ua-UK';
        $url = "$language/site/index";

        $request = $app->request;

        $request->setPathInfo($url);
        $parse = $urlManager->parseRequest($request);

        \Codeception\Util\Debug::debug("After parse request app language: {$app->language}");
        $this->tester->assertEquals($language, \Yii::$app->language);
    }
}