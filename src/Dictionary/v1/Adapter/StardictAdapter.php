<?php

namespace Dictionary\Adapter;

use Dictionary\Entity\Dictionary;

class StardictAdapter implements AdapterInterface
{
    /**
     * @var string
     */
    private $path;

    /**
     * StardictAdapter constructor.
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    public function __invoke($text): Dictionary
    {
        /* Set locale for read russian text */
        $locale = 'ru_RU.UTF-8';
        setlocale(LC_ALL, $locale);
        putenv('LC_ALL='.$locale);

        $transcription = [];
        $example = '';
        foreach (explode(' ', $text) as $string) {
            $cmd = "sdcv --data-dir " . $this->path . " -n --utf8-output --utf8-input '"
                . escapeshellarg($string) . "'";
            $output = shell_exec(
                $cmd
            );
            /* Получение транскрипции из разных словарей */
            /* <t>transcription</t> */
            preg_match('~\<t\>(.*)\<\/t\>~U', $output, $matches);
            /* [transcription] */
            preg_match('~\[(.*)\]~U', $output, $newMatches);
            if (isset($matches[1])) {
                $transcription[] = $matches[1];
            } elseif (isset($newMatches[1])) {
                $transcription[] = $newMatches[1];
            }

            if (str_word_count($text) == 1) {
                preg_match_all('~\d\>\s(\_.*\.\s)*(.*)~', $output, $matches);
                $tmpTranslations = $matches[2];

                foreach ($tmpTranslations as $row) {
                    $tmpExamples = explode(' _Ex: ', $row);
                    foreach ($tmpExamples as $tmpExample) {
                        preg_match('~^([\w\s\']+)\s?~', $tmpExample, $matches);
                        if (!empty($matches)) {
                            $example = trim($matches[0]);
                            break 2;
                        }
                    }
                }
            }
        }

        $dictionary = new Dictionary();
        $dictionary->setId(0);
        $dictionary->setText($text);
        $dictionary->setTranscription(implode(' ', $transcription));
        $dictionary->setExample($example);

        return $dictionary;
    }
}
