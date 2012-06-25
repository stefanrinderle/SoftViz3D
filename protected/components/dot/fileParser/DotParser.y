%name TP_
%declare_class {class DotParser}
%include_class
{
    // states whether the parse was successful or not
    public $successful = true;
    public $retvalue = 0;
    private $lex;
    private $internalError = false;

    function __construct($lex) {
        $this->lex = $lex;
    }
}

%token_prefix TP_

%stack_overflow {
    print_r("Giving up. XParser stack overflow");
}
   
%stack_size 30000

%parse_accept
{
    $this->successful = !$this->internalError;
    $this->internalError = false;
    $this->retvalue = $this->_retvalue;
}

%syntax_error
{
    $this->internalError = true;
    echo "Syntax Error on line " . $this->lex->line . ": token '" . 
        $this->lex->value . "' count ".$this->lex->counter." while parsing rule: ";
    foreach ($this->yystack as $entry) {
        echo $this->tokenName($entry->major) . '->';
    }
    foreach ($this->yy_get_expected_tokens($yymajor) as $token) {
        $expect[] = self::$yyTokenName[$token];
    }
	echo "\n";	
    throw new Exception('Unexpected ' . $this->tokenName($yymajor) . '(' . $TOKEN. '), expected one of: ' . implode(',', $expect));
}

start(res) ::= DIGRAPH id(name) OPENBRACE stmtList(stmts) CLOSEBRACE. {
	res = array(type => "main", label => name, content => stmts);
}

id(res) ::= ID(name). {
	res = name;
}

id(res) ::= QUOTMARK ID(name) QUOTMARK. {
	res = name;
}

stmtList(res)  ::= stmt(s1) stmtList(stmts). {
	res = array_merge(stmts, s1);
}

stmtList(res)  ::= stmt(content). { 
	res = content;
}

// Attr list
attrList(res) ::= OPENATTR aList(content) CLOSEATTR. {
	res = content;
}

attrList(res) ::= OPENATTR CLOSEATTR. {
	res = 0;
}

aList(res) ::= id(name) EQUALS aValue(value). {
	res = array(name => value);
}

aList(res) ::= id(name) EQUALS aValue(value) PUNCMARK aList(content). {
	res = array_merge(array(name => value), content);
}

aValue(res) ::= QUOTMARK aQuotValue(name) QUOTMARK. {
	res = name;
}

aQuotValue(res) ::= ID(value). {
	res = array(value);
}

aQuotValue(res) ::= ID(value) PUNCMARK aQuotValue(values). {
	res = array_merge(array(value), values);
}

aQuotValue(res) ::= ID(value) aQuotValue(values). {
	res = array_merge(array(value), values);
}

aValue(res) ::= ID(name). {
	res = name;
}

// Node stmt
stmt(res)  ::= id(name). { 
	res = array(array(type => "node", label => name));
}

stmt(res)  ::= id(name) attrList(content). { 
	res = array(array(type => "node", label => name, attr => content));
}

// Edge stmt
stmt(res)  ::= id(name1) EDGEOP id(name2). { 
	res = array(array(label => name1 . " -> " . name2, type => "edge", node1 => name1, node2 => name2));
}

stmt(res)  ::= id(name1) EDGEOP id(name2) attrList(content). {
	res = array(array(label => name1 . " -> " . name2, type => "edge", node1 => name1, node2 => name2, attr => content));
}

// Subgraph stmt
stmt(res)  ::= SUBGRAPH subgraph(content). {
	res = array(content);
}

subgraph(res) ::= id(name) OPENBRACE stmtList(stmts) CLOSEBRACE. {
	res = array(type => "sub", label => name, content => stmts);
}

subgraph(res) ::= id(name) OPENBRACE CLOSEBRACE. {
	res = array(type => "sub", label => name, content => array());
}