<?php
namespace common\components\locale\provider;

use yii\base\Component;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * Language provider from db
 * User: Ruslan Saiko
 * Date: 12.06.2016
 * Time: 23:34
 */
class DbLanguageProvider extends Component implements LanguageProviderInterface
{
    /**
     * @var string
     */
    public $db = 'db';
    /**
     * @var $table string language table
     */
    public $table;
    /**
     * @var string locale field (ex. en-US)
     */
    public $localeField;
    /**
     * @var string language field (ex. en)
     */
    public $languageField;

    /**
     * @var string|array|Expression $condition the conditions that should be put in the WHERE part.
     */
    public $languageCondition = null;

    /**
     * @inheritdoc
     */
    public function getLanguages()
    {
        $query = new Query();
        $result = $query
            ->select([$this->localeField])->addSelect($this->languageField)
            ->from($this->table)
            ->andFilterWhere($this->languageCondition)
            ->all(\Yii::$app->get($this->db));
        $data =  ArrayHelper::map($result, $this->localeField, $this->languageField);
        return $data;

    }
}