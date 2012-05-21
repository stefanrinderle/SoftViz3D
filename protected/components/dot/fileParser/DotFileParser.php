<?php

require_once 'PHP/LexerGenerator.php';

class DotFileParser extends CApplicationComponent {
	public $generate;
	
	public function init() {
		// Create Parser
		if ($this->generate) {
			exec('/Applications/XAMPP/xamppfiles/bin/php /Applications/XAMPP/xamppfiles/lib/php/pear/PHP/ParserGenerator/cli.php ' . dirname(__FILE__) . '/DotParser.y', $output, $return);
			
			$lex = new PHP_LexerGenerator(dirname(__FILE__) . '/DotLexer.plex');
		}
	}
	
	public function parseFile($dotFilePath) {
		$lex = new DotLexer(file_get_contents($dotFilePath));
		
		return $this->parse($lex);
	}
	
	public function parseString($string) {
		$lex = new DotLexer($string);
	
		return $this->parse($lex);
	}
	
	public function parseStringArray($array) {
		$result = "";
		foreach ($array as $value) {
			$result .= $value;
		}

		return $this->parseString($result);
	}
	
	private function parse($lex) {
		$parser = new DotParser($lex);
		while ($lex->yylex())  {
			$parser->doParse($lex->token, $lex->value);
		}
		$parser->doParse(0, 0);
		
		return $parser->retvalue;
	}
	
}

?>