<?php
namespace BBParser;

class BBNode extends Node {
    private $name = '', $type, $beginAt, $endAt, $raw, $children;
    
    public function getName()     { return $this->name; }
    public function getBeginAt()  { return $this->beginAt; }
    public function getEndAt()    { return $this->endAt; }
    public function getRaw()      { return $this->raw; }
    public function getChildren() { return $this->children; }
}
