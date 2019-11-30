<?

//TODO: Female names for queen cards? French and German diminutives too?
function maleDiminutive($tarot = 0)
{
	if ($tarot==1) { return "card"; }
	$names = array("Abe","Al","Ala","Alberto","Alex","Alf","Alfie","Ali","Ander","Andy","Arnie","Ash","Ashy","Auggy","August","Ava","Barney","Barry","Bart","Baz","Ben","Benji","Benny","Bert","Bertie","Biddy","Bill","Billy","Bob","Bobbie","Bobby","Bodie","Brad","Brady","Bram","Brayden","Brent","Brett","Bridey","Brodie","Burt","Cal","Carl","Carly","Charley","Charlie","Chas","Chaz","Chazza","Chesty","Chet","Chic","Chip","Chris","Chrissy","Chuck","Chucky","Clay","Clem","Clementine","Cliff","Clint","Coby","Cody","Coop","Curt","Dan","Danny","Dave","Davey","Daz","Denny","Dick","Dicky","Dom","Don","Donny","Dory","Doug","Drew","Dunny","Ed","Eddie","Eddy","Ferd","Frank","Frankie","Franky","Fred","Freddy","Gabe","Gaby","Gal","Garry","Gary","Gene","Geno","Geoff","Georg","Georgy","Gerie","Gero","Geron","Gerry","Gib","Gil","Gordy","Greg","Gregg","Gus","Gussie","Gussy","Hal","Hank","Hanky","Harris","Harry","Heath","Herb","Herbie","Hick","Hickey","Howie","Hunt","Ian","Ike","Irv","Iscariot","Ivy","Jack","Jacky","Jake","Jase","Jay","Jeff","Jeffie","Jeremy","Jerry","Jim","Jimmy","Joe","Joey","John","Johnny","Jon","Jonny","Jordy","Josh","Joshi","Judd","Jude","Judy","Julie","Kai","Keath","Ken","Kenny","Ker","Kerin","Kit","Kori","Lachy","Larry","Laurie","Leba","Lee","Len","Lenny","Leo","Leon","Lew","Lewie","Lewy","Lex","Liam","Liv","Livia","Log","Logy","Lonnie","Lori","Lou","Louie","Luke","Maaz","Maggie","Marc","Marcy","Mario","Mark","Marky","Marsh","Marshy","Marty","Marv","Matt","Matty","Max","Maxam","Maxi","Maxie","Maxinumisanic","Maxmillion","Maxus","Mayam","Meg","Mel","Mellie","Mick","Micky","Mike","Mikey","Milan","Milly","Mitch","Moe","Monty","Morg","Morris","Nat","Nate","Nath","Nathan","Natty","Ned","Neddie","Neddy","Nel","Nemo","Nick","Nicki","Nickie","Nicky","Nige","Nikki","Nob","Nobby","Norm","Nour","Ol","Oli","Oliver","Olivetta","Oliwa","Ollie","Olly","Orbie","Orman","Oz","Ozzy","Paddy","Pat","Patty","Paulie","Pauly","Peg","Peggy","Percy","Perry","Pete","Petey","Phil","Philly","Pig","Piggy","Quin","Quince","Quinn","Rafi","Ralph","Rand","Randy","Ray","Rayden","Reg","Reggie","Rench","Renchy","Rich","Rick","Ricky","Rob","Robby","Robert","Robin","Rod","Roddy","Ron","Ronny","Russ","Rusty","Ryan","Sal","Sam","Sammy","Sander","Sandy","Sid","Simon","Spence","Stan","Steve","Stevie","Stevo","Stu","Stuie","Ted","Teddy","Tel","Terry","Theo","Tim","Timmy","Toby","Toddy","Tom","Tommy","Tony","Topher","Travis","Trent","Trev","Ty","Tyron","Tyson","Val","Vester","Vet","Vic","Vin","Vince","Vinnie","Viv","Vivo","Vivy","Vor","Wagger","Wal","Wally","Walt","Wen","Wes","Wessie","Whizzy","Wiggy","Will","Willy","Wiz","Wizard","Woody","Xander","Xavi","Zac","Zach","Zack","Zag","Zags","Zak","Zig","Ziggy");
	return $names[array_rand($names)];
}

function qualityAdjective()
{
	// applicable to games or rules or variants - should be bland subjective terms (no "complicated", etc)
	
	return randWord("traditional","amusing","diverting","entertaining","popular","interesting","challenging","enjoyable","little-known","classic","underrated","over-rated","mediocre","tolerable");
}

function countryfromcode($code)
{
	if ($code == "en") { return "English"; }
	if ($code == "fr") { return "French"; }
	if ($code == "de") { return "German"; }
	return "European";
}
?>