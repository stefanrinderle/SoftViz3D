<?php
class DotLexer
{

    private $data;
    public $counter;
    public $token;
    public $value;
    public $node;
    public $line;
    private $state = 1;

    function __construct($data)
    {
        $this->data = $data;
        $this->counter = 0;
        $this->line = 1;
    }


    private $_yy_state = 1;
    private $_yy_stack = array();

    function yylex()
    {
        return $this->{'yylex' . $this->_yy_state}();
    }

    function yypushstate($state)
    {
        array_push($this->_yy_stack, $this->_yy_state);
        $this->_yy_state = $state;
    }

    function yypopstate()
    {
        $this->_yy_state = array_pop($this->_yy_stack);
    }

    function yybegin($state)
    {
        $this->_yy_state = $state;
    }




    function yylex1()
    {
        $tokenMap = array (
              1 => 0,
              2 => 0,
              3 => 1,
              5 => 0,
              6 => 0,
              7 => 0,
              8 => 0,
              9 => 0,
              10 => 0,
            );
        if ($this->counter >= strlen($this->data)) {
            return false; // end of input
        }
        $yy_global_pattern = '/\G(digraph)|\G(subgraph)|\G([a-zA-Z0-9_]+([a-zA-Z0-9_]+)?)|\G(\\{)|\G(\\})|\G(;)|\G(->)|\G(\n)|\G(.)/';

        do {
            if (preg_match($yy_global_pattern,$this->data, $yymatches, null, $this->counter)) {
                $yysubmatches = $yymatches;
                $yymatches = array_filter($yymatches, 'strlen'); // remove empty sub-patterns
                if (!count($yymatches)) {
                    throw new Exception('Error: lexing failed because a rule matched' .
                        ' an empty string.  Input "' . substr($this->data,
                        $this->counter, 5) . '... state START');
                }
                next($yymatches); // skip global match
                $this->token = key($yymatches); // token number
                if ($tokenMap[$this->token]) {
                    // extract sub-patterns for passing to lex function
                    $yysubmatches = array_slice($yysubmatches, $this->token + 1,
                        $tokenMap[$this->token]);
                } else {
                    $yysubmatches = array();
                }
                $this->value = current($yymatches); // token value
                $r = $this->{'yy_r1_' . $this->token}($yysubmatches);
                if ($r === null) {
                    $this->counter += strlen($this->value);
                    $this->line += substr_count($this->value, "\n");
                    // accept this token
                    return true;
                } elseif ($r === true) {
                    // we have changed state
                    // process this token in the new state
                    return $this->yylex();
                } elseif ($r === false) {
                    $this->counter += strlen($this->value);
                    $this->line += substr_count($this->value, "\n");
                    if ($this->counter >= strlen($this->data)) {
                        return false; // end of input
                    }
                    // skip this token
                    continue;
                } else {
                    $yy_yymore_patterns = array(
        1 => array(0, "\G(subgraph)|\G([a-zA-Z0-9_]+([a-zA-Z0-9_]+)?)|\G(\\{)|\G(\\})|\G(;)|\G(->)|\G(\n)|\G(.)"),
        2 => array(0, "\G([a-zA-Z0-9_]+([a-zA-Z0-9_]+)?)|\G(\\{)|\G(\\})|\G(;)|\G(->)|\G(\n)|\G(.)"),
        3 => array(1, "\G(\\{)|\G(\\})|\G(;)|\G(->)|\G(\n)|\G(.)"),
        5 => array(1, "\G(\\})|\G(;)|\G(->)|\G(\n)|\G(.)"),
        6 => array(1, "\G(;)|\G(->)|\G(\n)|\G(.)"),
        7 => array(1, "\G(->)|\G(\n)|\G(.)"),
        8 => array(1, "\G(\n)|\G(.)"),
        9 => array(1, "\G(.)"),
        10 => array(1, ""),
    );

                    // yymore is needed
                    do {
                        if (!strlen($yy_yymore_patterns[$this->token][1])) {
                            throw new Exception('cannot do yymore for the last token');
                        }
                        $yysubmatches = array();
                        if (preg_match('/' . $yy_yymore_patterns[$this->token][1] . '/',
                              $this->data, $yymatches, null, $this->counter)) {
                            $yysubmatches = $yymatches;
                            $yymatches = array_filter($yymatches, 'strlen'); // remove empty sub-patterns
                            next($yymatches); // skip global match
                            $this->token += key($yymatches) + $yy_yymore_patterns[$this->token][0]; // token number
                            $this->value = current($yymatches); // token value
                            $this->line = substr_count($this->value, "\n");
                            if ($tokenMap[$this->token]) {
                                // extract sub-patterns for passing to lex function
                                $yysubmatches = array_slice($yysubmatches, $this->token + 1,
                                    $tokenMap[$this->token]);
                            } else {
                                $yysubmatches = array();
                            }
                        }
                        $r = $this->{'yy_r1_' . $this->token}($yysubmatches);
                    } while ($r !== null && !is_bool($r));
                    if ($r === true) {
                        // we have changed state
                        // process this token in the new state
                        return $this->yylex();
                    } elseif ($r === false) {
                        $this->counter += strlen($this->value);
                        $this->line += substr_count($this->value, "\n");
                        if ($this->counter >= strlen($this->data)) {
                            return false; // end of input
                        }
                        // skip this token
                        continue;
                    } else {
                        // accept
                        $this->counter += strlen($this->value);
                        $this->line += substr_count($this->value, "\n");
                        return true;
                    }
                }
            } else {
                throw new Exception('Unexpected input at line' . $this->line .
                    ': ' . $this->data[$this->counter]);
            }
            break;
        } while (true);

    } // end function


    const START = 1;
    function yy_r1_1($yy_subpatterns)
    {

  $this->token = DotParser::TP_DIGRAPH;
//  echo "digraph: ".$this->value."\n";
    }
    function yy_r1_2($yy_subpatterns)
    {

  $this->token = DotParser::TP_SUBGRAPH;
//  echo "subgraph: ".$this->value."\n";
    }
    function yy_r1_3($yy_subpatterns)
    {

  $this->token = DotParser::TP_ID;
//  echo "id: ".$this->value."\n";
    }
    function yy_r1_5($yy_subpatterns)
    {

  $this->token = DotParser::TP_OPENBRACE;
//  echo "openBrace: ".$this->value."\n";
    }
    function yy_r1_6($yy_subpatterns)
    {

  $this->token = DotParser::TP_CLOSEBRACE;
//  echo "closeBrace: ".$this->value."\n";
    }
    function yy_r1_7($yy_subpatterns)
    {

  $this->token = DotParser::TP_SEMICOLON;
//  echo "semicolon: ".$this->value."\n";
    }
    function yy_r1_8($yy_subpatterns)
    {

  $this->token = DotParser::TP_EDGEOP;
//  echo "edgeop: ".$this->value."\n";
    }
    function yy_r1_9($yy_subpatterns)
    {

  return false;
    }
    function yy_r1_10($yy_subpatterns)
    {

  return false;
    }

}