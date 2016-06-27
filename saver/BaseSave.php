<?php
/**
 * Created by PhpStorm.
 * User: Ruslan
 * Date: 15.06.2016
 * Time: 13:14
 */

namespace bl\locale\saver;


use yii\base\Object;

abstract class BaseSave extends Object implements LanguageSaveInterface
{
    /** @var string */
    protected $_key;

    /**
     * BaseSave constructor.
     * @param string $key
     * @param array|null $config
     */
    public function __construct($key, array $config = null)
    {
        $this->_key = $key;
        parent::__construct($config);
    }


}