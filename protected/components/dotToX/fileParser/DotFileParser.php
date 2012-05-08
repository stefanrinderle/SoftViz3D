<?php

class DotFileParser extends CApplicationComponent
{

	public function parse($dotFilePath)
	{
		// Create Parser
		//TODO: not working
		//print_r('/Applications/XAMPP/xamppfiles/bin/php /Applications/XAMPP/xamppfiles/lib/php/pear/PHP/ParserGenerator/cli.php ' . dirname(__FILE__) . '/DotParser.y');
 		exec('/Applications/XAMPP/xamppfiles/bin/php /Applications/XAMPP/xamppfiles/lib/php/pear/PHP/ParserGenerator/cli.php ' . dirname(__FILE__) . '/DotParser.y');
 		
		// Create Lexer
		require_once 'PHP/LexerGenerator.php';
		
		$lex = new PHP_LexerGenerator(dirname(__FILE__) . '/DotLexer.plex');

		include_once("DotParser.php");
		include_once("DotLexer.php");
		
		$lex = new DotLexer(file_get_contents($dotFilePath));
		
		$parser = new DotParser($lex);
		while ($lex->yylex())  {
			$parser->doParse($lex->token, $lex->value);
		}
		$parser->doParse(0, 0);
		
		return $parser->retvalue;
	}
}

?>