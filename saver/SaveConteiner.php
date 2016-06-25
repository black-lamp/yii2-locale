<?php
namespace bl\locale\saver;

/**
 * Class SaveConteiner
 * @package bl\locale\saver
 */
class SaveConteiner implements LanguageSaveInterface
{
    /** @var LanguageSaveInterface[] */
    protected $_savers = [];

    public function save($value)
    {
        $isSave = false;
        
        foreach ($this->_savers as $save) {
            if ($save->save($value)) {
                $isSave = true;
            }
        }
        return $isSave;
    }

    public function add(LanguageSaveInterface $saver)
    {
        $this->_savers[] = $saver;
    }

}