<?php
namespace BBParser;

require_once __DIR__ . DIRECTORY_SEPARATOR .'InvalidLocException.php';
require_once __DIR__ . DIRECTORY_SEPARATOR .'InvalidChildrenException.php';
require_once __DIR__ . DIRECTORY_SEPARATOR .'InvalidRangeException.php';

abstract class Node {
    public $type = '';
    public $value = '',
        $range = [2],
        $loc   = [2],
        $children = [],
        $raw = '';

    public function __construct($value, $range, $loc, $raw) {
        $f = new \ReflectionClass($this);
        $this->type = $f->getShortName();
        $this->setValue($value);
        $this->setRange($range);
        $this->setLoc($loc);
        $this->setRaw($raw);
    }

    public function accept(Visitor $visitor) {
        $visitor->visit($this);
    }

    public function getType() { return $this->type; }

    public function getValue() { return $this->value; }
    public function setValue($value) { $this->value = $value; }

    public function getLoc() { return $this->loc; }
    public function setLoc($loc) {
        $c = count($loc);
        if($c === 2 && isset($loc['start']) && $loc['start'] instanceof Point && isset($loc['end']) && $loc['end'] instanceof Point) {
            $this->loc = $loc;
        } else {
            if($c < 2) {
                throw new InvalidLocException("The argument does not contain 2 elements");
            } else {
                throw new InvalidLocException("The argument does not contains Point(s)");
            }
        }
    }

    public function getRange() { return $this->range; }
    public function setRange($range) {
        $c = count($range);
        if($c === 2) {
            $this->range = $range;
        }
        else {
            throw new InvalidRangeException("Range contains only {$c} elements");
        }
    }

    public function getChildren() { return $this->children; }
    public function setChildren($children) {
        if(is_array($children)) {
            $this->children = $children;
        } else {
            throw new InvalidChildrenException("The argument is not an array");
        }
    }

    public function setChild($index, $child) {
        if(!$child instanceof Node) {
            throw new InvalidChildrenException("Child is not a node");
        } else {
            $this->children[$index] = $child;
        }
    }

    public function appendChild($child) {
        if(!$child instanceof Node) {
            throw new InvalidChildrenException("Child is not a node");
        } else {
            $this->children[] = $child;
        }
    }

    public function getChild($index) {
        return isset($this->children[$index]) ? $this->children[$index] : [];
    }

    public function getLastChild() {
        return ($c = count($this->children)) && $c > 0 ? $this->children[$c-1] : [];
    }

    public function getRaw() { return $this->raw; }
    public function setRaw($raw) {
        $this->raw = $raw;
    }

}
