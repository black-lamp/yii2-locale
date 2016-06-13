<?php

class UrlManagerTest extends \yii\codeception\TestCase
{

    public $appConfig = '@tests/unit/_config.php';

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

    // tests
    public function testCreateUrl()
    {
        $test = \Yii::$app->urlManager;
        \Codeception\Util\Debug::debug($test->createUrl(['site/index', []]));
    }

    public function testCrateUrlWithBaseUrl()
    {

    }
    public function testCrateUrlWithOutBaseUrl()
    {

    }
}