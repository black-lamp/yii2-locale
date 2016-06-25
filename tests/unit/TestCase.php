<?php
namespace bl\locale\tests\unit;

use bl\locale\UrlManager;

/**
 * Created by PhpStorm.
 * User: Ruslan
 * Date: 24.06.2016
 * Time: 23:31
 */
class TestCase extends \yii\codeception\TestCase
{

    public $appConfig = '@tests/unit/_config.php';

    /**
     * @var \yii\web\Application
     */
    protected $app;

    /**
     * @param null $config
     * @return \yii\base\Application
     * @throws \yii\base\InvalidConfigException
     */
    protected function mockApplication($config = null)
    {
        $config = isset($config) ? $config : require(\Yii::getAlias($this->appConfig));
        if (!isset($config['class'])) {
            $config['class'] = 'yii\web\Application';
        }
        return \Yii::createObject($config);
    }

    public function __construct()
    {
        $this->app = $this->mockApplication();
    }


}