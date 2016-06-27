# yii2-locale
```php
'components' => [
  ...
    'urlManager' => [
        'class' => 'bl\locale\UrlManager',
        languageProvider' => [
            'class' => 'bl\locale\provider\DbLanguageProvider',
            'db' => 'db2',
            'table' => 'language',
            'localeField' => 'lang_id',
            'languageCondition' => ['active' => true],
        ],
        'lowerCase' => true,
        'useShortSyntax' => true,
        'languageKey' => 'language',
        'showDefault' => false,
      ],
]
```
