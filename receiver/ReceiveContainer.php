<?php
/**
 * Created by PhpStorm.
 * User: Ruslan
 * Date: 14.06.2016
 * Time: 16:29
 */

namespace bl\locale\receiver;

use common\components\locale\provider\LanguageProviderInterface;


class ReceiveContainer implements LanguageReceiveInterface
{
    /** @var LanguageReceiveInterface[] */
    protected $receiver = [];

    public function getLanguage()
    {
        foreach ($this->receiver as $receiver) {
            if (!empty(($language = $receiver->getLanguage()))) {
                return $language;
            }
        }
    }

    public function addReceiver(LanguageReceiveInterface $languageProvider)
    {
        $this->receiver[] = $languageProvider;
    }
}