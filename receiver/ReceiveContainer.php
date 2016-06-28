<?php
namespace bl\locale\receiver;

class ReceiveContainer implements LanguageReceiveInterface
{
    /** @var LanguageReceiveInterface[] */
    protected $receiver = [];

    public function getLanguage()
    {
        foreach ($this->receiver as $receiver) {
            $language = $receiver->getLanguage();
            if (!empty($language)) {
                return $language;
            }
        }
    }

    public function addReceiver(LanguageReceiveInterface $languageProvider)
    {
        $this->receiver[] = $languageProvider;
    }
}