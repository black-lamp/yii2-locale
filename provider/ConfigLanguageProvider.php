<?php
/**
 * Created by PhpStorm.
 * User: Ruslan
 * Date: 13.06.2016
 * Time: 1:32
 */

namespace common\components\locale\provider;


use yii\base\Component;

class ConfigLanguageProvider extends Component implements LanguageProviderInterface
{

    public $languages;

    public function getLanguages()
    {
        return $this->languages;
    }
}