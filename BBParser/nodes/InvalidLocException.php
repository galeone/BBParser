<?php
namespace BBParser;

use Exception;

class InvalidLocException extends Exception {
    public function __construct($message) {
        parent::__construct($message);
    }
}
