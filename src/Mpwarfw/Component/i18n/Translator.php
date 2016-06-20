<?php
namespace Mpwarfw\Component\i18n;

use Mpwarfw\Component\i18n\Exception\TranslationNotFoundException;
use Mpwarfw\Component\i18n\Exception\TranslationsFileNotSetException;
use Symfony\Component\Yaml\Parser;

class Translator
{
    private $parser;
    private $translations = null;
    
    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }
    
    public function setTranslationsFile($translationsFile)
    {
        $this->translations = $this->parser->parse(file_get_contents($translationsFile));
    }
    
    public function translate($message)
    {
        if (is_null($this->translations)) {
            throw new TranslationsFileNotSetException("Translations file not set!!");
        }

        foreach ($this->translations as $entry){
            foreach ($entry as $key=>$translation){
                if($key == $message) {
                    return $translation;
                }
            }
        }

        throw new TranslationNotFoundException("Translation not found for $message!!");

    }
}