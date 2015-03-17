<?php

namespace BBParser;

class BBParser {
    const CANT_CONTAIN_BBCODE = 0;
    const CAN_CONTAIN_BBCODE = 1;

    private $beginBB = [
        'user'    => ['regex' => 'user'],
        'project' => ['regex' => 'project'],
        'url'     => ['regex' => 'url(="?(?P<url>.+?)"?)?', 'function' => ['name' => 'filter_var', 'params' => ['url', FILTER_VALIDATE_URL]], 'info' => self::CANT_CONTAIN_BBCODE],
        'img'     => ['regex' => 'img'],
        'b'       => ['regex' => 'b'],
        'cur'     => ['regex' => 'cur'],
        'i'       => ['regex' => 'i'],
        'small'   => ['regex' => 'small'],
        'big'     => ['regex' => 'big'],
        'del'     => ['regex' => 'del'],
        'u'       => ['regex' => 'u'],
        'code'    => ['regex' => 'code=\w{1,34}'],
        'gist'    => ['regex' => 'gist'],
        'youtube' => ['regex' => 'youtube'],
        'yt'      => ['regex' => 'yt'],
        'video'   => ['regex' => 'video'],
        'twitter' => ['regex' => 'twitter'],
        'music'   => ['regex' => 'music'],
        'm'       => ['regex' => 'm'],
        'math'    => ['regex' => 'math'],
        'wiki'    => ['regex' => 'wiki=\w{2}'],
        'quote'   => ['regex' => 'quote(?P<author>=\w{1,34})?', 'function' => ['name' => 'filter_var', 'params' => ['author', FILTER_VALIDATE_URL]], 'info' => self::CAN_CONTAIN_BBCODE], //todo
        'spoiler' => ['regex' => 'spoiler(?P<label>=\w{1,34})?', 'function' => ['name' => 'filter_var', 'params' => ['label', FILTER_VALIDATE_URL]], 'info' => self::CAN_CONTAIN_BBCODE],
        'list'    => ['regex' => 'list((?P<type> type="a|A|i|I|1")|(?P<start> start="[0-9]+")|(?P<start-type> start="[0-9]+" type="a|A|i|I|1")|(?P<type-start> type="a|A|i|I|1" start="[0-9]+"))?']
    ];

    private $bodyBB = [
        'user'    => ['regex' => '\w{3,90}', 'info' => self::CANT_CONTAIN_BBCODE],
        'project' => ['regex' => '\w{3,30}', 'info' => self::CANT_CONTAIN_BBCODE],
        'url'     => ['regex' => '(?P<url>.+)', 'function' => ['name' => 'filter_var', 'params' => ['url', FILTER_VALIDATE_URL]], 'info' => self::CANT_CONTAIN_BBCODE],
        'img'     => ['regex' => '(?P<url>.+)', 'function' => ['name' => 'filter_var', 'params' => ['url', FILTER_VALIDATE_URL]], 'info' => self::CANT_CONTAIN_BBCODE],
        'b'       => ['regex' => '.+', 'info' => self::CAN_CONTAIN_BBCODE],
        'cur'     => ['regex' => '.+', 'info' => self::CAN_CONTAIN_BBCODE],
        'i'       => ['regex' => '.+', 'info' => self::CAN_CONTAIN_BBCODE],
        'small'   => ['regex' => '.+', 'info' => self::CAN_CONTAIN_BBCODE],
        'big'     => ['regex' => '.+', 'info' => self::CAN_CONTAIN_BBCODE],
        'del'     => ['regex' => '.+', 'info' => self::CAN_CONTAIN_BBCODE],
        'u'       => ['regex' => '.+', 'info' => self::CAN_CONTAIN_BBCODE],
        'code'    => ['regex' => '.+', 'info' => self::CANT_CONTAIN_BBCODE],
        'gist'    => ['regex' => '[0-9a-z]+', 'info' => self::CANT_CONTAIN_BBCODE],
        'youtube' => ['regex' => '(?P<url>.+)', 'function' => ['name' => 'filter_var', 'params' => ['url', FILTER_VALIDATE_URL]], 'info' => self::CANT_CONTAIN_BBCODE],
        'yt'      => ['regex' => '(?P<url>.+)', 'function' => ['name' => 'filter_var', 'params' => ['url', FILTER_VALIDATE_URL]], 'info' => self::CANT_CONTAIN_BBCODE],
        'video'   => ['regex' => '(?P<url>.+)', 'function' => ['name' => 'filter_var', 'params' => ['url', FILTER_VALIDATE_URL]], 'info' => self::CANT_CONTAIN_BBCODE],
        'twitter' => ['regex' => '(?P<url>.+)', 'function' => ['name' => 'filter_var', 'params' => ['url', FILTER_VALIDATE_URL]], 'info' => self::CANT_CONTAIN_BBCODE],
        'music'   => ['regex' => '(?P<url>.+)', 'function' => ['name' => 'filter_var', 'params' => ['url', FILTER_VALIDATE_URL]], 'info' => self::CANT_CONTAIN_BBCODE],
        'm'       => ['regex' => '.+', 'info' => self::CANT_CONTAIN_BBCODE],
        'math'    => ['regex' => '.+', 'info' => self::CANT_CONTAIN_BBCODE],
        'wiki'    => ['regex' => '.+', 'info' => self::CAN_CONTAIN_BBCODE],
        'quote'   => ['regex' => '.+', 'info' => self::CAN_CONTAIN_BBCODE],
        'spoiler' => ['regex' => '.+', 'info' => self::CAN_CONTAIN_BBCODE],
        'list'    => ['regex' => '(?\[\*\].+\n)+', 'info' => self::CAN_CONTAIN_BBCODE]
    ];

    private $endBB = [
        'user'    => 'user',
        'project' => 'project',
        'url'     => 'url',
        'img'     => 'img',
        'b'       => 'b',
        'cur'     => 'cur',
        'i'       => 'i',
        'small'   => 'small',
        'big'     => 'big',
        'del'     => 'del',
        'u'       => 'u',
        'code'    => 'code',
        'gist'    => 'gist',
        'youtube' => 'youtube',
        'yt'      => 'yt',
        'video'   => 'video',
        'twitter' => 'twitter',
        'music'   => 'music',
        'm'       => 'm',
        'math'    => 'math',
        'wiki'    => 'wiki',
        'quote'   => 'quote',
        'spoiler' => 'spoiler',
        'list'    => 'list'
    ];

    private $ast, $lastLeaf;
    private $currentPosition, $oldPosition;
    private $string;

    const TEXT_NODE_T = 'textNode';
    const BBCODE_NODE_T = 'bbcodeNode';

    public function __construct() {
        $this->reset();
    }

    private function reset() {
        $this->currentPosition = 0;
        $this->oldPosition = 0;
        $this->astPosition = 0;
        $this->string = '';
        $this->ast = $this->createNode('root', self::TEXT_NODE_T);
        $this->lastLeaf = &$this->ast;
    }

    private function createNode($name, $type) {
        echo "Creating $name\n";
        $node = [
            'name'    => $name,
            'type'    => $type,          
            'beginAt' => $this->oldPosition,
            'endAt'   => $this->currentPosition,
            'raw'     => substr($this->string, $this->oldPosition, $this->currentPosition - $this->oldPosition),
            'child'   => null
        ];
        $this->oldPosition = $this->currentPosition;
        return $node;
    }

    private function createTextNode($name) {
        return $this->createNode($name, static::TEXT_NODE_T);
    }

    private function createBBCodeEmptyNode($name) {
        $node = $this->createNode($name, static::BBCODE_NODE_T);
        $node['begin'] = null;
        $node['body']  = null;
        $node['end']   = null;
        return $node;
    }

    public function appendChild(&$child) {
        echo "Appending {$child['name']} to {$this->lastLeaf['name']}\n";
        $this->lastLeaf['child'] = &$child;
        $this->lastLeaf = &$child;
    }

    private function isValidTagName($tagName, $tagNameWithOptions, &$parameters) {
        $parameters = [];
        return isset($this->beginBB[$tagName]) && preg_match('#^'.$this->beginBB[$tagName]['regex'].'$#iu',$tagNameWithOptions, $parameters);
    }

    private function parseBegin() {
        while(isset($this->string[$this->currentPosition]) && $this->string[$this->currentPosition] !== '[') {
            ++$this->currentPosition;
        }

        $textNode = $this->createTextNode('text');
        $this->appendChild($textNode);

        $tagNameWithOptions = '';
        while(isset($this->string[$this->currentPosition]) && $this->string[$this->currentPosition] !== ']') {
            $tagNameWithOptions .= $this->string[$this->currentPosition];
            ++$this->currentPosition;
        }
        $tagNameWithOptions .= $this->string[$this->currentPosition];
        ++$this->currentPosition;

        $options = '';

        $tagNameWithOptions = trim($tagNameWithOptions,'[]');

        $name = $tagNameWithOptions;

        if(($spacePos = strpos($tagNameWithOptions,'=')) !== false) {
            $name = substr($tagNameWithOptions, 0, $spacePos);
            $options = substr($tagNameWithOptions, $spacePos);
        }
        echo "NAME:: $name\n";

        $validTagOpened = false;
        $parameters = [];
        if($this->isValidTagName($name, $tagNameWithOptions, $extractParameters)) {
            if(isset($this->beginBB[$name]['function'])) {
                $validatorName = $this->beginBB[$name]['function']['name'];
                $validatorParameters = $this->beginBB[$name]['function']['params'];
                foreach($validatorParameters as $id => $validatorParam) {
                    if(isset($extractParameters[$validatorParam])) {
                        $validatorParameters[$id] = $extractParameters[$validatorParam];
                    }
                }
                var_dump($validatorParameters);
                if(false !== call_user_func_array($validatorName, $validatorParameters)) {
                    $BBNode = $this->createBBCodeEmptyNode($name);
                    $BBNode['begin'] = $this->createBBCodeEmptyNode('begin_'.$name);
                    $this->appendChild($BBNode);
                } else {
                    $validationFailedNode = $this->createTextNode('begin_'.$name.'_failed_parameters_validation');
                    $this->appendChild($validationFailedNode);
                }
            } else {
                $BBNode = $this->createBBCodeEmptyNode($name);
                $BBNode['begin'] = $this->createTextNode('begin_'.$name);
                $this->appendChild($BBNode);
            }
        } else {
            $invalidTag = $this->createTextNode('invalid_tag');
            $this->appendChild($invalidTag);
        }
    }

    public function parse(&$string) {
        $this->string = $string;
        $this->parseBegin();
        return $this->ast;
    }
}

$wat = new BBParser();

$str = 'You wat [url="http://www.google.com"]asd[/url] [user]nigga[/user]?
[url]lel lol[/url]
[url="banana"]ciao[/url]
[small]asdasd[/small]
[small][small][big]nigger[/big][/small][/smal]';

print_r($wat->parse($str));
?>
