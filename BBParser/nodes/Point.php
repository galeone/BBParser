<?php
namespace BBParser;

require_once __DIR__ . DIRECTORY_SEPARATOR .'Node.php';

final class Point {
    public $line = 0, $column = 0;

    public function __construct($line, $column) {
        $this->line = $line;
        $this->column = $column;
    }
}
