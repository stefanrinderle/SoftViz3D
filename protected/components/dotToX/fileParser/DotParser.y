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

start(res) ::= DIGRAPH ID(name) OPENBRACE stmtList(stmts) CLOSEBRACE. {
	res = array(type => "main", label => name, content => stmts);
}

stmtList(res)  ::= stmt(s1) SEMICOLON stmtList(stmts). {
	res = array_merge(stmts, s1);
}

stmtList(res)  ::= stmt(content) SEMICOLON. { 
	res = content;
}

stmt(res)  ::= ID(name). { 
	res = array(array(type => "node", label => name));
}

stmt(res)  ::= ID(name1) EDGEOP ID(name2). { 
	res = array(array(type => "edge", node1 => name1, node2 => name2));
}

stmt(res)  ::= SUBGRAPH subgraph(content). {
	res = array(content);
}

subgraph(res) ::= ID(name) OPENBRACE stmtList(stmts) CLOSEBRACE. {
	res = array(type => "sub", label => name, content => stmts);
}