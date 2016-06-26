<?php

namespace bl\locale\provider;


interface LanguageProviderInterface
{
    /**
     * Returns an array of example ['en-us'=> 'english', 'ru' => null, 'ru-ru' => 'russian', 'ua-uk' => null, ...]
     * @return array
     */
    public function getLanguages();
}