<?php
namespace BBParser;

interface Visitor {
    public function visit(BBNode $node);
    public function visit(TextNode $node);
}
