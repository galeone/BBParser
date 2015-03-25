<?php
namespace BBParser;

use Exception;

class InvalidChildrenExtension extends Exception {
    public function __construct($message) {
        parent::__construct($message);
    }
}
