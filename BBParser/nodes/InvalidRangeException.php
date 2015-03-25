<?php
namespace BBParser;

use Exception;

class InvalidRangeExtension extends Exception {
    public function __construct($message) {
        parent::__construct($message);
    }
}
