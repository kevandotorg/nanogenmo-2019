<?
	function randword()
	{
		$args = func_get_args();
		
		$wordcount = count($args);
		
		if ($wordcount==2)
		{ $position = rand(0,1); }
		else
		{ $position = sqrt(rand(0, $wordcount*$wordcount - 1)); }
		return $args[$wordcount-1-$position];		
	}
	
	function purerandword()
	{
		$args = func_get_args();
		return $args[array_rand($args)];
	}
	
	function heading($text,$h=3)
	{
		return "<h$h>$text</h$h>";
	}

	function paragraph($text,$tags=1)
	{
		$text = preg_replace("/\.(\)?)([A-Z0-9\(])/",".$1 $2",$text);
		$text = preg_replace("/\b([aA]) ([AEIOUaeiou8])/","$1n $2",$text);
		$text = preg_replace("/\b([aA]) <b>(\"?[AEIOUaeiou8])/","$1n <b>$2",$text);
		$text = preg_replace("/[tT]he The/","The",$text); // sometimes major arcana names pop up in odd places
		$text = preg_replace("/ \((or|the) <b>card<\/b>\)/","",$text); // avoid giving nicknames to tarot cards
		$text = preg_replace("/arcanas(\S)/","arcana$1",$text); // fix plural
		$text = preg_replace("/\.\./",".",$text); // lazy fix for double punctuation I saw somewhere
		$text = preg_replace("/\. (\(?)([a-z])/",". ".ucfirst("$1$2"),$text); // fix uncapitalised sentence starts
		$text = preg_replace("/eg\. ([A-|])/","eg. ".lcfirst("$1"),$text); // fix uncapitalised sentence starts
		$text = preg_replace("/ 1 points/"," 1 point",$text); // fix plural
		$text = preg_replace("/leafs/","leaves",$text); // fix plural
		$text = preg_replace("/matchs/","matches",$text); // fix plural
		$text = preg_replace("/pennys/","pennies",$text); // fix plural
		if ($tags==1) { return "<p>$text</p>\n"; }
		else { return $text; }
	}

	function randsuit($specialdeck=0)
	{
		if ($specialdeck==1) { $suits = array("wand","cup","pentacle","sword","major arcana card"); }
		elseif ($specialdeck==2) { $suits = array("heart","acorn","bell","leaf"); }
		else { $suits = array("heart","club","diamond","spade"); }
		return $suits[array_rand($suits)];
	}

	function cardname($card)
	{
		if (preg_match("/^(.+)([CDHSJABLwpscm])$/",$card,$matches))
		{
			if ($matches[2] == "C") { return "the ".rankword($matches[1])." of clubs"; }
			if ($matches[2] == "D") { return "the ".rankword($matches[1])." of diamonds"; }
			if ($matches[2] == "H") { return "the ".rankword($matches[1])." of hearts"; }
			if ($matches[2] == "S") { return "the ".rankword($matches[1])." of spades"; }
			
			if ($matches[2] == "A") { return "the ".rankword($matches[1])." of acorns"; }
			if ($matches[2] == "B") { return "the ".rankword($matches[1])." of bells"; }
			if ($matches[2] == "L") { return "the ".rankword($matches[1])." of leaves"; }

			if ($matches[2] == "w") { return "the ".rankword($matches[1])." of wands"; }
			if ($matches[2] == "p") { return "the ".rankword($matches[1])." of pentacles"; }
			if ($matches[2] == "s") { return "the ".rankword($matches[1])." of swords"; }
			if ($matches[2] == "c") { return "the ".rankword($matches[1])." of cups"; }
			
			if ($matches[2] == "m")
			{
				$arcana = array("The Fool", "The Magician", "The High Priestess", "The Empress", "The Emperor", "The Hierophant", "The Lovers", "The Chariot", "Strength", "The Hermit", "Wheel of Fortune", "Justice", "The Hanged Man", "Death", "Temperance", "The Devil", "The Tower", "The Star", "The Moon", "The Sun", "Judgement", "The World");
				return $arcana[$matches[1]];		
			}
			
			if ($card == "1J") { return "the red joker"; }
			if ($card == "2J") { return "the black joker"; }
		}
		return "an unidentified card ($card)";
	}
	
	function gametypename($n)
	{
		$words = array("unknown","trick-taking","climbing","melding","matching");
		return $words[$n];
	}
	function numberword($n)
	{
		$words = array("zero","one","two","three","four",
        "five","six","seven","eight","nine","ten",
        "eleven","twelve","thirteen","fourteen","fifteen",
        "sixteen","seventeen","eighteen","nineteen",
		"twenty","twenty-one");
		
		if (preg_match("/^\d+$/",$n))
		{ 
			if ($n>-1 && $n<22) { return $words[$n]; }
		}
		return $n;
	}
	
	function nthword($n)
	{
		$words = array("zeroth","first","second","third","fourth","fifth","sixth","seventh","eighth","ninth","tenth","eleventh","twelfth","thirteenth","fourteenth","fifteenth","sixteenth","seventeenth","eighteenth","nineteenth","twentieth","twenty-first");
		
		if ($n>-1 && $n<22) { return $words[$n]; }
		else { return "[invalid number]"; }
	}

	function rankword($char)
	{
		if ($char=="K") { return "King"; }
		if ($char=="Q") { return "Queen"; }
		if ($char=="J") { return "Jack"; }
		if ($char=="N") { return "Knight"; }
		if ($char=="P") { return "Page"; }
		if ($char=="A") { return "Ace"; }
		if ($char=="U") { return "Unter"; }
		if ($char=="O") { return "Ober"; }
		return $char;
	}

	function rankheight($char)
	{
		// just for sorting purposes, should never try to convert these into anything
		if ($char=="K") { return "99"; }
		if ($char=="Q") { return "98"; }
		if ($char=="J") { return "97"; }
		if ($char=="N") { return "96"; }
		if ($char=="P") { return "95"; }
		if ($char=="U") { return "94"; }
		if ($char=="O") { return "93"; }
		if ($char=="A") { return "1"; }
		return "$char";
	}
	
?>
