<?php

class File {

    /** @var string */
    private $fileName;

    /** @var array */
    private $arrayWithNumbers = [];

    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }

    public function setArrayWithNumbers($array)
    {
        $this->arrayWithNumbers = $array;
    }

    public function readFile(): array
    {
        $file = file_get_contents($this->fileName);
        $lines = explode(PHP_EOL, $file);

        foreach ($lines as $line) {
            $pair = array_map('intval', explode(' ', $line));
            $this->arrayWithNumbers[] = $pair;
        }
        return $this->arrayWithNumbers;
    }

    public function writeToFile()
    {
        $positiveNumbers = [];
        $negativeNumbers = [];

        foreach ($this->arrayWithNumbers as $number) {
            if ($number < 0) {
                $negativeNumbers[] = $number;
            } else {
                $positiveNumbers[] = $number;
            }
        }
        file_put_contents('positive.txt', implode(PHP_EOL, $positiveNumbers));
        file_put_contents('negative.txt', implode(PHP_EOL, $negativeNumbers));

        return;
    }
}

class Calculator {

    /** @var array */
    private $arrayWithNumbers;
    /** @var array */
    private $result = [];
    /** @var string */
    private $action;

    public function __construct($array, $action)
    {
        $this->arrayWithNumbers = $array;
        $this->action = $action;

        foreach ($array as $pair) {
            if ($this->action === 'sum') {
                $this->result[] = $pair[0] + $pair[1];
            }

            if ($this->action === 'substract') {
                $this->result[] = $pair[0] - $pair[1];
            }

            if ($this->action === 'multiply') {
                $this->result[] = $pair[0] * $pair[1];
            }

            if ($this->action === 'divide') {
                $this->result[] = $pair[0] / $pair[1];
            }
        }
    }

    public function getResult()
    {
        return $this->result;
    }

}

function makeAction($filename, $action) {

    $file = new File();
    $file->setFileName($filename);
    $data = $file->readFile();

    $newCalc = new Calculator($data, $action);
    $result = $newCalc->getResult();

    $newData = new File();
    $newData->setArrayWithNumbers($result);
    $newData->writeToFile();

    echo 'Сделано';
}

makeAction('numbers.txt', 'sum');