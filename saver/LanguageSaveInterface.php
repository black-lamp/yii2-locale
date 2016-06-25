<?php
/**
 * Created by PhpStorm.
 * User: Ruslan
 * Date: 15.06.2016
 * Time: 13:04
 */

namespace bl\locale\saver;

/**
 * Interface LanguageSaveInterface
 * @package bl\locale\saver
 */
interface LanguageSaveInterface
{
    /**
     * @param $value string
     * @return bool retrun true if language save in storage
     */
    public function save($value);
}