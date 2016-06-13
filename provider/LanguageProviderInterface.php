<?php

namespace common\components\locale\provider;


interface LanguageProviderInterface
{
    /**
     * Returns an array of example ['en-us'=> 'en', 'ru' => null, 'ru-ru' => 'ru', 'ua-uk' => null, ...]
     * @return array
     */
    public function getLanguages();
}