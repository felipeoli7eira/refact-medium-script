<?php

abstract class Importer
{
    public static function import(string $path, string $fileDelimiter = ','): void
    {
        try {
            if (empty(trim($path))) {
                throw new \InvalidArgumentException('The path cannot be empty' . PHP_EOL, __LINE__);
            }

            if (! in_array($fileDelimiter, [',', ';'])) {
                throw new \InvalidArgumentException('The file delimiter must be , or ;' . PHP_EOL, __LINE__);
            }

            if (! file_exists($path)) {
                throw new \InvalidArgumentException('The file does not exist' . PHP_EOL, __LINE__);
            }

            if (! is_file($path)) {
                throw new \InvalidArgumentException('The path is not a file' . PHP_EOL, __LINE__);
            }

            if (! is_readable($path)) {
                throw new \InvalidArgumentException('The file is not readable' . PHP_EOL, __LINE__);
            }

            $file = file($path);

            $restContent = array_slice($file, 1);
            $arrayParseContent = array_map(fn ($row) => explode($fileDelimiter, $row), $restContent);

            $firstRow = $arrayParseContent[0];
            $firstColumnOfFirstRow = $firstRow[0];

            $urlOfFirstColumnOfFirstRow = parse_url($firstColumnOfFirstRow)['host'];
            $doamin = explode('.', $urlOfFirstColumnOfFirstRow)[1];
            $parseCaseDomain = explode('-', $doamin);
            $ucFirsts = array_map('ucfirst', $parseCaseDomain);
            $joinWords = implode('', $ucFirsts);

            $methodToCall = 'handler' . $joinWords . 'Data';

            if (! method_exists(__CLASS__, $methodToCall)) {
                throw new \InvalidArgumentException('The method does not exist' . PHP_EOL, __LINE__);
            }

            $callable_name = '';
            if (! is_callable([__CLASS__, $methodToCall], true, $callable_name)) {
                throw new \InvalidArgumentException('The method is not callable' . PHP_EOL, __LINE__);
            }

            $callable_name($arrayParseContent);
        } catch (\Throwable $throwable) {
            echo $throwable->getMessage();
        }
    }

    private static function handlerBoatsData(array $data): void
    {
        var_dump(__LINE__);
    }

    private static function handlerBoatsSpecData(array $data): void
    {
        var_dump(__LINE__);
    }
}
