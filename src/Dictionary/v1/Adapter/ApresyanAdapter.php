<?php

namespace Dictionary\Adapter;

use Dictionary\Entity\Dictionary;

class ApresyanAdapter implements AdapterInterface
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
        $example = '';
        foreach (explode(' ', $text) as $string) {
            $output = shell_exec("sdcv " . $this->path . " '" . escapeshellarg($string)) ."'";

            /* Получение всех аглийских примеров примеров более 3х символов */
            preg_match_all('~[^Found]^[a-zA-Z\s\,\.\(\)\']{5,}~m', $output, $matches);
            /* Удаление пробелов у всех строк */
            $matches = array_map('trim', $matches[0]);
            /* Сортировка строк по длине, по убыванию */
            usort(
                $matches,
                function ($first, $second) {
                    $firstLen = strlen($first);
                    $secondLen = strlen($second);
                    if ($firstLen == $secondLen) {
                        return 0;
                    }
                    return $firstLen > $secondLen ? -1 : 1;
                }
            );

            /* Удаление пустых строк и строк больше 90 символов */
            foreach ($matches as $key => $value) {
                if (empty($value) || strlen($value) > 90) {
                    unset($matches[$key]);
                }
            }

            $example = array_shift($matches);
        }

        $dictionary = new Dictionary();
        $dictionary->setId(0);
        $dictionary->setText($text);
        $dictionary->setExample($example);

        return $dictionary;
    }
}
