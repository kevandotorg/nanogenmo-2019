<?
	define("TRICK_TAKER", 1);
	define("CLIMBING_GAME", 2);
	define("MELDING_GAME", 3);
	define("MATCHING_GAME", 4);

	function generateCardGame($gametype=0)
	{
		$rules = "";
		$basicStructure = rand(1,3);
		$rarity = 8;

		$countrycode = purerandword("en","fr","de");
		$gameName = villageName($countrycode);

		$alsoName = ""; $thirdName = "";
		
		while ($alsoName == $thirdName)
		{
			$alsoName = similarName($gameName);
			$thirdName = similarName($gameName);
		}
		
		$minplayers = rand(2,3);
		$maxplayers = rand(3,6);
		if ($minplayers == $maxplayers) { $maxplayers++; }

		$adjectives = array();
		$facts = array();

		//$gametype = rand(1,4);

		// cards used

		$rules .= heading($gameName,2);
		
		$decktype = randword("a standard 52 card pack","a standard 52 card pack");
		$specialdeck=0;
		$bomb = ""; $rocket = "";
		
		if (rand(1,15)==1) { $decktype = "a tarot deck"; $specialdeck=1; }
		if (rand(1,15)==1 && $countrycode == "de") { $decktype = "a 32-card German deck"; $specialdeck=2; }
		if (rand(1,15)==1 && $countrycode == "fr") { $decktype = "a 32-card French deck"; $specialdeck=3; }
		
		$deck = array();

		$cardpara = "!INTRODUCTION!";

		$ranknote = "";
		$removenote = "";
		$easytoremove = 0;
		
		if ($specialdeck==1)
		{ $ranks = array("K","Q","N","P","10","9","8","7","6","5","4","3","2","A"); $easytoremove = 1; }
		elseif ($specialdeck==2)
		{ $ranks = array("A","K","O","U","10","9","8","7"); }
		elseif ($specialdeck==3)
		{ $ranks = array("A","K","Q","J","10","9","8","7"); }			
		elseif (rand(1,4)==1)
		{ $ranks = array("10","9","8","7","6","5","4","3","2","A"); $removenote = "Remove court cards. "; }
		elseif (rand(1,10)==1)
		{ $ranks = array("2","A","K","Q","J","10","9","8","7","6","5","4","3"); $ranknote .= "2s are higher than Aces"; }
		elseif (rand(1,10)==1)
		{ $ranks = array("3","2","A","K","Q","J","10","9","8","7","6","5","4"); $ranknote .= "3s and 2s are ranked above Aces"; }
		elseif (rand(1,10)==1)
		{
			$ranks = array("A","K","Q","J","10","9","8","7","6","5","4","3","2");

			$promoted = rand(0,count($ranks)-1);
			$promotedrank = $ranks[$promoted];
			unset($ranks[$promoted]);
			array_unshift($ranks, $promotedrank);
			$ranks = array_values($ranks);
			$ranknote .= rankword($promotedrank)."s are higher than aces";
		}
		elseif (rand(1,2)==1)
		{
			$ranks = array("A","K","Q","J","10","9","8","7","6","5","4","3","2");
			//$ranknote = "Aces are high";
			$easytoremove = 1;
		}
		else
		{
			$ranks = array("K","Q","J","10","9","8","7","6","5","4","3","2","A");
			//$ranknote = "Aces are low";
			$easytoremove = 1;
		}

		if ($removenote == "")
		{
			$fromwhere = "";
			
			if ($specialdeck==1)
			{ $fromwhere = " from the minor arcana"; }
			
			if ($specialdeck==2) 
			{} // small deck, leave it alone
			else if ($ranknote != "")
			{} // mentioned cards when changing ranks, may be contradicted if cards are removed at random
			else if ($gametype == MELDING_GAME)
			{} // need all the cards we can get for this
			else if (rand(1,5)==1) // remove a card
			{
				$removed = rand(0,count($ranks)-1);
				$removedrank = $ranks[$removed];
				unset($ranks[$removed]);
				$ranks = array_values($ranks);
				
				$removenote .= "Remove all ".rankword($removedrank)."s$fromwhere. "; 
			}
			elseif (rand(1,8)==1 && $easytoremove == 1) // remove range of cards
			{
				$start = rand(0,count($ranks)-4);
				$end = rand($start+1,count($ranks)-1);
				if ($end-$start>4) { $end = $start+4; }
				
				//print"<li>Before removing cards:"; print_r($ranks); 
				
				if ($end==$start+1)
				{ $removedranks = rankword($ranks[$end])."s and ".rankword($ranks[$start+1])."s"; }
				else
				{ $removedranks = rankword($ranks[$end])."s through ".rankword($ranks[$start+1])."s"; }
			
				for ($i=$start+1; $i<$end+1; $i++)
				{
					unset($ranks[$i]);
				}
				$ranks = array_values($ranks);
				
				//print"<li>After removing $removedranks ($start/$end):"; print_r($ranks); 
				
				$removenote .= "Remove ".$removedranks."$fromwhere.";
			}
			elseif (rand(1,6)==1) // remove two cards
			{
				$removed = rand(0,count($ranks)-1);
				$removedrank = $ranks[$removed];
				unset($ranks[$removed]);
				$ranks = array_values($ranks);

				$removed = rand(0,count($ranks)-1);
				$removedrank2 = $ranks[$removed];
				unset($ranks[$removed]);
				$ranks = array_values($ranks);

				$removenote .= "Remove ".rankword($removedrank)."s and ".rankword($removedrank2)."s$fromwhere.";
			}
		}
		
		$rankphrase = "";
		foreach ($ranks as $rank)
		{ $rankphrase .= $rank.", "; }
		$rankphrase = preg_replace("/, ([^,]+), $/",", $1",$rankphrase);
		//$rankphrase = preg_replace("/ /","&nbsp;",$rankphrase);

		if ($specialdeck==1)
		{
			$rankphrase = preg_replace("/K,/","King,",$rankphrase);
			$rankphrase = preg_replace("/Q,/","Queen,",$rankphrase);
			$rankphrase = preg_replace("/P,/","Page,",$rankphrase);
			$rankphrase = preg_replace("/N,/","Knight,",$rankphrase);
		}
		else if ($specialdeck==2)
		{
			$rankphrase = preg_replace("/U,/","Unter,",$rankphrase);
			$rankphrase = preg_replace("/O,/","Ober,",$rankphrase);
			$rankphrase = preg_replace("/K,/","King,",$rankphrase);
		}
		$cardpara .= $removenote;
		
		if ($ranknote != "")
		{ $cardpara .= "$ranknote: cards rank $rankphrase."; }
		else
		{ $cardpara .= "Cards rank $rankphrase."; }

		$therewillberuns = 0;
		if (rand(1,$rarity)==1 && ($gametype == MELDING_GAME || $gametype == CLIMBING_GAME))
		{
			$therewillberuns = 1;
			$cardpara.= rankword($ranks[sizeof($ranks)-1])."s can be counted either high or low in a run, such that ".rankword($ranks[sizeof($ranks)-1])."-".rankword($ranks[sizeof($ranks)-2])."-".rankword($ranks[sizeof($ranks)-3])."
			and ".rankword($ranks[1])."-".rankword($ranks[0])."-".rankword($ranks[sizeof($ranks)-1])." are both valid runs (but ".rankword($ranks[0])."-".rankword($ranks[sizeof($ranks)-1])."-".rankword($ranks[sizeof($ranks)-2])." is not).";
		}

		if ($specialdeck!=1)
		{
			if (rand(1,$rarity)==1) { $cardpara .= "Include two jokers, one red and one black."; array_unshift($deck, "1J"); array_unshift($deck, "2J"); }
			elseif ( rand(1,20)==1) { $cardpara .= "Include one red joker."; array_unshift($deck, "1J"); }
		}

		// build the deck array
		foreach ($ranks as $rank)
		{
			if ($specialdeck==1)
			{
				array_unshift($deck, $rank."w");
				array_unshift($deck, $rank."p");
				array_unshift($deck, $rank."s");
				array_unshift($deck, $rank."c");
			}
			elseif ($specialdeck==2)
			{
				array_unshift($deck, $rank."H");
				array_unshift($deck, $rank."B");
				array_unshift($deck, $rank."A");
				array_unshift($deck, $rank."L");
			}
			else
			{
				array_unshift($deck, $rank."C");
				array_unshift($deck, $rank."D");
				array_unshift($deck, $rank."H");
				array_unshift($deck, $rank."S");
			}
		}
		
		if ($specialdeck==1)
		{
			for ($i=0; $i<21; $i++)
			{
				array_unshift($deck, $i."m");
			}
		}

		$rules .= paragraph($cardpara);
				
		$playpara = "";
		
		$handsize = 999;
		while ($handsize*$maxplayers>sizeof($deck))
		{
			$handsize = rand(5,15);
			if (sizeof($deck)<31) { $handsize = rand(3,15); }
			if (sizeof($deck)<10) { $handsize = rand(1,15); }
		}
	
		if ($gametype == MELDING_GAME && $handsize>10) { $handsize -= 6;}
		
		$handsizeword = numberWord($handsize);
		$badsuit = randsuit($specialdeck);
		$slangsuit = $badsuit; 
		if ($slangsuit == "major arcana card") { $slangsuit = "the arcana"; }

		$sticheln = "";
		$countdown = 0; $currency = "";
		$positivepoints = 0;
		$noneremain = 0;
		$maorule = ""; $bartok = 0; $explainmao = "";
					
		// SETUP
		$rules .= heading("Setup");

		if (rand(1,3)==1 && $handsize>10 && $gametype != MELDING_GAME && $gametype != MATCHING_GAME)
		{
			$playpara .= "Deal out cards until none remain.";
			$noneremain = 1;
		}
		else
		{
			if (rand(1,$rarity*3)==1 && ($gametype == CLIMBING_GAME || $gametype == MELDING_GAME || $gametype == MATCHING_GAME))
			{
				$countdown = 1; $currency = randword("coin","counter","penny","token","poker chip","match");
				$playpara .= "Each player starts with $handsizeword ".$currency."s. Each round, deal each player as many cards as they have ".$currency."s.";
				array_push($facts,"hand sizes gradually decrease");
			}
			else
			{
				$playpara .= randword("Each player is dealt $handsizeword cards.","Deal $handsizeword cards per player.","Deal $handsizeword cards to each player.","Each player is dealt $handsizeword cards.");
				if ($handsize>8 && $maxplayers>3 && rand(1,2)==1) { $playpara .= "With more than three players, deal ".numberWord($handsize-rand(3,4))." per player."; }
			}
		}

		$numberoftricks = $handsize;
		if ($noneremain == 1) { $numberoftricks = -1; }

		if (rand(1,$rarity)==1 && $noneremain == 0) { $playpara .= "The ".randword("player to the right of the dealer","player to the left of the dealer","dealer")." receives ".randword("an extra","one fewer")." card. "; }

		if (rand(1,$rarity*3)==1)
		{
			$playpara .= "Any player who was dealt no ".randsuit($specialdeck)."s ".randword("must","may")." reveal their hand: all players must throw in their hands, and the cards are shuffled and re-dealt.";
		}

		$rules .= paragraph($playpara);
		$playpara = "";
		$wildmeld=0;
		$cheat = 0;
		$seconddiscard = 0;
		$shithead = 0;
		
		if ($gametype == MELDING_GAME || $gametype == MATCHING_GAME)
		{
			if (rand(1,$rarity*2)==1 && $gametype == MATCHING_GAME)
			{
				if (rand(1,$rarity*2)==1)
				{
					$playpara .= "Deal two cards to form two face-up discard piles; when every player has seen the cards, turn them both face-down.";
					$seconddiscard = 1;
					array_push($facts,"there are two discard piles");
				}
				else				
				{ $playpara .= "Deal one card to form a face-up discard pile; when every player has seen it, turn it face-down."; }
				$cheat = 1;
				array_push($facts,"players may lie about their cards");
				array_push($adjectives,randword("deceptive","crafty","sneaky","deceitful","duplicitous"));
			}
			elseif (rand(1,$rarity*2)==1)
			{
				$playpara .= "Deal one card to form a face-up discard pile, and another to form a second discard pile.";
				$seconddiscard = 1;
				array_push($facts,"there are two discard piles");
			}
			else
			{
				$playpara .= "Deal one card to form a face-up discard pile.";
			}
			if (rand(1,$rarity)==1)
			{
				$wildmeld = 1;
				$playpara .= "Deal another card face-up and place it to one side where players can see it: cards of this rank are considered to be <b>jokers</b> instead of their usual rank.";
				if (in_array("1J",$deck) && !in_array("2J",$deck)) { $playpara .= " (If the dealt card is literally the joker card, shuffle it back in and deal a replacement.)"; }
			}

			$playpara .= "Undealt cards form the <b>stock</b>.";
		}
		
		$kitty = ""; $nomoredealing = 0; $nameofdeck = "deck";
		if ($handsize*$maxplayers<sizeof($deck)-6 && rand(1,$rarity)==1)
		{
			$kitty = randword("kitty","crib","pot","box","talon","cradle","rack","pit","claw","crypt","heap","stash","reliquary","coffer","crate","grapnel","hook");
			if ($noneremain == 1)
			{ $playpara .= "Each player chooses one card from their hand and plays it into a face-down <b>\"$kitty\"</b>, which is shuffled."; }
			else if ($handsize*$maxplayers>sizeof($deck)-10 && $gametype != MELDING_GAME && $gametype != MATCHING_GAME)
			{ $playpara .= "The undealt cards form a face-down <b>\"$kitty\"</b>."; $nomoredealing = 1; }
			else
			{ $playpara .= "Deal ".numberword(rand(3,5))." cards to form a face-down <b>\"$kitty\"</b>."; }
			$nameofdeck = $kitty;
		}
		
		if (rand(1,$rarity*2)==1 && $gametype != TRICK_TAKER) // Shithead
		{
			$shithead = 1;
			$playpara .= "Deal each player a row of three face-down cards: for each of these cards deal another card face-up on top of it.";
		}


		$trumps = 0; $antitrumps = 0; $choosetrumpslater = 0;
		
		if (rand(1,3)==1 || $gametype != TRICK_TAKER)
		{
			// no trumps
		}
		elseif (rand(1,$rarity*4)==1) { $choosetrumpslater = 1; }
		else
		{
			$trumps = 1;
			if (rand(1,$rarity*4)==1)
			{
				$playpara .= "Place the top card of the $nameofdeck face-down: this determines trump suit, but is not revealed (and does not apply) until the first time a player plays a suit that does not match the lead suit. ";
				array_push($adjectives,randword("suspenseful","tense"));
				array_push($facts,"trumps are not revealed immediately");
			}
			else if ($noneremain == 1)
			{ $playpara .= randword(randword("The dealer","The player to the dealer's left")." calls a trump suit before looking at their hand.","Reveal the final card as it is dealt; it determines trump suit.","Trumps alternate club/diamonds/hearts/spades for each deal.",ucfirst(randsuit($specialdeck))."s are always trumps.")." "; }
			else
			{ $playpara .= randword("Turn the top card of the $nameofdeck to determine trump suit.","Reveal the top card of the $nameofdeck to determine trump suit.",randword("The dealer","The player to the dealer's left")." calls a trump suit before looking at their hand.","Reveal the final card as it is dealt; it determines trump suit.","Trumps alternate club/diamonds/hearts/spades for each deal.",ucfirst(randsuit($specialdeck))."s are always trumps.")." "; }

			if (preg_match("/discard/",$playpara) && in_array("1J",$deck)) { $playpara .= "(Jokers may not be discarded in this way.)"; }
			if (preg_match("/discard/",$playpara)) { $numberoftricks -= 1; }

			if (rand(1,$rarity)==1 && !preg_match("/(face-down)/",$playpara))
			{
				$antitrumps = 1;
				if (preg_match("/discard/",$playpara))
				{ $playpara .= "At the start of each hand, before looking at their cards,the dealer calls any suit other than the trump suit as <b>anti-trumps</b>."; }
				else
				{ $playpara .= "Without looking at their hand, the dealer then calls any suit other than the trump suit as <b>anti-trumps</b>."; }
				array_push($adjectives,randword("vindictive","cruel","spiteful"));
				array_push($facts,"there are both trumps and anti-trumps");
			}
			
			if (strpos($playpara,"top card") && rand(1,$rarity)==1)
			{ $playpara .= "If a player has the ".rankword($ranks[sizeof($ranks)-1])." of trumps they may at this point <b>\"".randword("rob","steal","loot","swipe","lift","pinch","pilfer","plunder")."\"</b> the revealed trump card, ".randword("taking it into their hand and discarding any other card face-down","swapping the ".rankword($ranks[sizeof($ranks)-1])." for it")."."; }
			
			if ($specialdeck==1)
			{ $playpara = preg_replace("/club\/diamonds\/hearts\/spades/","cups/pentacles/swords/wands/major-arcana",$playpara); }
			if ($specialdeck==2)
			{ $playpara = preg_replace("/club\/diamonds\/hearts\/spades/","hearts/bells/acorns/leaves",$playpara); }
			
			if (preg_match("/(reveal|top card)/",$playpara) && in_array("1J",$deck)) { $playpara .= "If a joker is revealed, there is no trump."; }
			
			if (rand(1,$rarity)==1)
			{ $playpara .= "If any player has the ".rankword($ranks[0])." of trumps in their hand, they may optionally at this point score one point by revealing it."; }
		}
		if ($specialdeck==1)
		{ 
			if (rand(1,3)==1 && $trumps == 0 && $gametype == TRICK_TAKER)
			{ $playpara .= "The major arcana are trumps"; $trumps = 1; }
			else
			{ $playpara .= "The major arcana is considered to be its own suit"; }
			$playpara .= ".";
		}

		$rules .= paragraph($playpara);
	
		$nullo = 0; $pig = "";
		if (rand(1,20)==1 && $gametype == TRICK_TAKER)
		{
			if ($specialdeck==1)
			{ $rules .= paragraph("Each hand is played either \"high\" or \"low\". Before the hand each player chooses a major or minor arcana card from their hand, and these are revealed simultaneously: if all cards are major arcana the hand is played low, otherwise it is played high."); }
			elseif ($specialdeck==2)
			{ $rules .= paragraph("Each hand is played either \"high\" or \"low\". Before the hand each player chooses a plant (leaves/acorns) or non-plant (hearts/bells) card from their hand, and these are revealed simultaneously: if all cards are plants the hand is played low, otherwise it is played high."); }
			else
			{ $rules .= paragraph("Each hand is played either \"high\" or \"low\". Before the hand each player chooses a red or black card from their hand, and these are revealed simultaneously: if all cards are red the hand is played low, otherwise it is played high."); }
					
			$nullo = 1;
			array_push($adjectives,randword("uneasy"));
			array_push($facts,"hands are played high or low");
		}
		
		if (rand(1,$rarity)==1)
		{
			$choosepig = "may choose to";
			//if ($gametype == MELDING_GAME) { $choosepig = "must"; }
			
			if ($specialdeck==1)
			{
				$time = 999;
				$pigcard = "";
				while (!strpos($pigcard, "m") && $time>0) // pick major arcana only, if possible
				{
					$pigcard = $deck[array_rand($deck)];
					$time--;
				}
				$pig = cardname($pigcard);			
				$rules .= paragraph("A player who holds $pig $choosepig reveal it at this point.");
				$pigname = $pig; // no nickname needed for major arcana
			}
			else
			{
				$pigcard = $deck[array_rand($deck)];
				$pig = cardname($pigcard);
				$pigname = "the ".randword("sheep","pig","cow","goat","duck","goose","horse","bull","piglet","hen","lamb","turkey","donkey","chicken","duckling","rat","mouse","beast","bird");
				$rules .= paragraph("A player who holds $pig (<b>$pigname</b>) $choosepig reveal it at this point.");
			}
			array_push($facts,"players must keep track of \"$pigname\"");
		}
								
		if (rand(1,$rarity*4)==1)
		{
			$firstsuit = ""; $secondsuit = "";
			while ($firstsuit == $secondsuit)
			{
				$firstsuit = randsuit($specialdeck);
				$secondsuit = randsuit($specialdeck);
			}
			
			$godcard = cardname($deck[array_rand($deck)]);
			$playpara = "";
			if (rand(1,2)==1)
			{
				$bartok = 1;
				$maorule = randword("bonus","mystic","occult")." ".randword("rule","law","order","code","edict","decree","bylaw");			
				$playpara .= "After the first hand of the game, players may have to obey additional <b>".$maorule."s</b>.";
				array_push($facts,"players invent their own extra rules");
				array_push($adjectives,randword("creative","imaginative","inventive","curious","increasingly complex"));
			}			
			else
			{
				$maorule = randword("secret","god","mystic","occult","unseen","unspoken","chairman")." ".randword("rule","law","order","code","edict","decree","bylaw");
				$playpara .= "Before players look at their cards for the first hand, ".randword("the dealer invents a","a randomly-chosen player invents a","the dealer and the player to their right each invents their own")." <b>$maorule</b>
								and does not inform the other players of it.";
				array_push($facts,"players must obey secret rules");
				array_push($adjectives,randword("deductive","analytical","mysterious","logical","cerebral"));
			}

			if ($gametype == MELDING_GAME)			
			{
				$playpara .= "(A $maorule can be a constraint on card play, such as <i>\"cannot play a meld that lacks a $firstsuit\"</i> or <i>\"can't discard a ".rankword($ranks[rand(0,count($ranks)-1)])."\"</i>,
						or a bonus ability, eg. <i>\"after playing a meld with a ".rankword($ranks[rand(0,count($ranks)-1)])." in it, ".randword("take another turn","discard a card for free","play order is reversed","the next player skips their turn")."\"</i>.)";
			}
			else
			{
				$playpara .= "(A $maorule can be a constraint on card play, such as <i>\"cannot play a ".randword("$firstsuit after a $secondsuit")."\"</i>,
						or a bonus ability, eg. <i>\"after playing a ".rankword($ranks[rand(0,count($ranks)-1)]).", ".randword("take another turn","draw a card","play order is reversed","the next player skips their turn")."\"</i>.)";
			}
					
			if ($bartok==0) { $playpara .= "A $maorule remains in force until the end of the game."; }
			$rules .= paragraph($playpara);

		}

		if (rand(1,$rarity)==1 && $nomoredealing == 0)
		{
			$exchange = randword("exchange","redraw","mulligan","swap","shift","switch","barter","turnabout");
			$rules .= paragraph(randword("After looking at their cards, any player may suggest a <b>$exchange</b> of a specific number of cards. If all players agree to it, each player discards that many cards face-down and is dealt replacements. (If insufficient cards remain, a $exchange may not be suggested.) When no further ".$exchange."s are requested, play proceeds.",
										 "After looking at their cards, any player may suggest a <b>$exchange</b> of a single card. If all players agree to it, each player discards one card face-down and is dealt a replacement. (If insufficient cards remain, a $exchange may not be suggested.) When no further ".$exchange."s are requested, play proceeds."));
			array_push($facts,"opening hands can be negotiated");
		}
		$playpara = "";

		$bidding = 0; $blindbid = 0;
		if (rand(1,$rarity)==1 && $gametype == TRICK_TAKER)
		{
			// BIDDING
			$bidding = 1;
			$rules .= heading("Bidding");
			if (rand(1,4)==1)
			{
				$playpara = "Players simultaneously bid the number of tricks they think they will take during the hand, to a minimum of zero.";
			}
			else
			{
				$playpara = "Starting with the ".randword("dealer","player to the left of the dealer").", each player bids the number of tricks they think they will take during the hand, to a minimum of zero.";
				if (rand(1,$rarity)==1) { $blindbid = 1; $playpara .= "A player may bid \"blind\" by bidding without looking at their cards".randword("","",", but this bid can only be zero")."."; }
				if (rand(1,3)>1) $playpara .= "The final bidder cannot bid a number that would cause the total bids to equal the number of tricks available.";
			}
			
			array_push($facts,"players predict how many tricks they will take during a hand");
			array_push($adjectives,randword("thoughtful","subtle"));

			$rules .= paragraph($playpara);
		}

		$landlord = "";
		if (rand(1,$rarity/2)==1 && $kitty!="" && $minplayers>2)
		{
			if ($bidding == 0) { $rules .= heading("Bidding"); }
			
			$landlord = randword("squire","banker","lord","baron","noble","patrician","duke","count","innkeeper","house","guard","dragon","gangster","gendarme");
			$playpara = "Starting with the dealer, players ";
			if ($bidding == 1) { $playpara .= "then "; }
			$playpara .= "bid to become the <b>$landlord</b>. A bid may be a number from one to ".numberword($maxplayers)." (which must be larger than all previous bids), or a pass. As soon as two consecutive players pass, or a bid of ".numberword($maxplayers)." is made, the highest bidder becomes the $landlord and takes the $kitty into their hand. If all players pass, the hand is redealt.";
			$rules .= paragraph($playpara);
			$kitty = "";			

			array_push($facts,"players ".randword("work together","gang up")." against the \"$landlord\"");
			array_push($adjectives,randword("collaborative","combative","competitive","antagonistic","ruthless"));
		}

		$playpara = "";
		// PLAY
		$rules .= heading("Play");

		if ($gametype == TRICK_TAKER)
		{
			if ($choosetrumpslater == 1) { $playpara .= "The suit of first card played in the hand will determine the trump for the entire hand."; }

			if ($numberoftricks == -1 && rand(1,3)==1 && $choosetrumpslater == 0)
			{
				$playpara .= "Whoever has ".cardname($deck[array_rand($deck)])." plays it to lead the first trick.";
			}
			else
			{
				if ($landlord != "")
				{ $playpara .= "The $landlord"; }
				else
				{ $playpara .= randword("The dealer","The player to the dealer's left"); }
				$playpara .= " ".randword("starts by leading","leads","may lead")." ";
				if ($trumps == 0 || $choosetrumpslater == 1)
				{ $playpara .= "any card."; }
				else
				{ $playpara .= randword("any card","any card","any card which is not the trump suit (unless they only have trump cards)")."."; }
			}

			if (rand(1,$rarity)==1) // sticheln
			{
				$sticheln = randword("weak","poor","poison","hollow","small","pale","quiet","thin","slight","bad","wrong","dim","dull","faint","rotten","waste");
				if ($trumps == 1)
				{ $playpara .= "If the card led is not a trump, its suit"; }
				else
				{ $playpara .= "The suit led"; }
				$playpara .= " is the <b>".$sticheln."</b> suit for the trick.";
				
				array_push($facts,"the led suit is weaker than the rest");
			}

			if (rand(1,$rarity)==1)
			{
				if ($trumps == 0)
				{ $playpara .= randword("Players must follow the lead suit if they can"); }
				else
				{ $playpara .= randword("Players may either follow the lead suit or play a trump"); }
				
				$playpara .= ", ";
				$playpara .= randword("otherwise they may play any card","otherwise any card may be played","otherwise they may play any card").".";
			}
			elseif (rand(1,$rarity)==1)
			{ $playpara .= randword("Players need not follow suit.","Players are not required to follow suit."); }
			else
			{
				$playpara .= randword("Players must follow suit if possible");
				$playpara .= ", ";
				if (rand(1,$rarity)==1 && $trumps == 1)
				{
					$playpara .= "otherwise must play a trump card if they have one, ";
				}
				else if (rand(1,$rarity)==1)
				{
					$playpara .= "otherwise must play ".randword("a higher card","a suit which hasn't yet been played","a rank which hasn't yet been played")." if they can do so, ";
				}
				$playpara .= randword("otherwise may play any card","otherwise any card may be played","otherwise they may play any card").".";
			}

			if (in_array("1J",$deck) && rand(1,2)==1)
			{		
				if (in_array("2J",$deck)) { $playpara .= "The jokers are "; }
				else { $playpara .= "The joker is ";}
				$playpara .= "wild and can be played as any card.";
			}
			else
			{		
				if (in_array("2J",$deck) && $trumps == 1) { $playpara .= randword("The red joker (or <b>".maleDiminutive()."</b>) counts as the highest card in the trump suit, the black joker (the <b>".maleDiminutive()."</b>) ".randword("second-highest","second-highest","lowest")."."); }
				else if (in_array("1J",$deck) && $trumps == 1) { $playpara .= randword("The red joker (or <b>".maleDiminutive()."</b>) counts as the highest card in the trump suit.","The red joker (or <b>".maleDiminutive()."</b>) is considered to be of the trump suit, but lower than all other trumps."); }
			}

			$rules .= paragraph($playpara);

			$restrictpara = "";
			if (rand(1,$rarity)==1 && in_array("1J",$deck)) { $restrictpara .= "If the first card of a trick is a joker, subsequent players must play their highest $badsuit (if they have one)."; }
			if (rand(1,$rarity)==1) { $restrictpara .= "A player cannot lead with a $badsuit until after a $badsuit has been played to a previous trick, unless their hand contains nothing but ".$badsuit."s. (Playing the first $badsuit of the game is known as <b>\"".randword("dropping","blasting","smashing","breaking","cracking","releasing","bursting","freeing","popping","tapping","flourishing","throwing","breaking out the","getting out the")." ".$slangsuit."s\"</b>.) "; }
			if (rand(1,$rarity)==1) { $restrictpara .= ucfirst($badsuit)."s cannot be played during the first trick, except by a player who has nothing but ".$badsuit."s in their hand."; }
			if ($pig != "" && rand(1,2)==1)
			{
				$restrictpara .= "If $pigname was revealed, it cannot be ";
				if (rand(1,3)==1) { $restrictpara .= "played into the first trick in which its suit is led, nor "; }
				$restrictpara .= "used to lead a trick, unless it is the player's only card of that suit.";
			}
			$rules .= paragraph($restrictpara);
			
			$boosted = 0;
			if (rand(1,$rarity)==1) { $rules .= paragraph("If a trick is led with a ".rankword($ranks[rand(0,count($ranks)-1)]).", it is played as a <b>".randword("boost","double","lift","shove","push","rush","rocket","fire","heavy","two-headed")."</b> trick: after each player has played a card, it continues around the table again until each player has played two cards to the trick."); $boosted = 1; }
			
			if ($maorule != "")
			{
				$rule = "rule";
				if (preg_match("/^[a-z]+ ([a-z]+)$/",$maorule,$matches)) { $rule = $matches[1]; }
				
				if ($bartok == 1)
				{
					$playpara = "If the ".$maorule."s mean that a player has no valid play open to them, they must pass.";
					$rules .= paragraph($playpara);
				}
				else
				{
					$example = randword("that player leading the next trick","changing the play order","swapping cards in play","awarding or removing points","ending the trick early");
					if ($trumps == 1) { $example = randword("changing the trump","cancelling trumps","changing the play order","swapping cards in play","awarding or removing points","ending the trick early"); }
					
					$playpara = "Players must obey ".$maorule."s. If a player breaks one without realising, the player who invented the $rule informs them that they have done so (without explaining the rule): the ".$rule."-breaker must take back their play";
					$playpara .= " and play again. If no play is possible, they may pass.";
					$playpara .= "If a $maorule causes an effect to occur when a card is played (such as $example), the ".$rule."'s inventor informs the players of this when it happens.";
					$rules .= paragraph($playpara);
				}
			}

			$playpara = "";
			
			$playpara .= "The trick is won by ";
			if ($trumps == 1)
			{
				$playpara .= randword("the highest trump");
				if ($antitrumps == 1)
				{ $playpara .= ", unless there were no trumps or an anti-trump was played to the trick, in which case it is won by "; }
				else
				{ $playpara .= ", or if it contains no trump by "; }
			}				
			
			if ($sticheln != "")
			{ $playpara .= "the highest card not in the $sticheln suit (with the earliest card played breaking ties), or the highest $sticheln card if all cards were $sticheln. "; }
			else
			{ 
				$playpara .= randword("the highest card of the lead suit","the highest card of the lead suit","the highest card irrespective of suit (with the earliest card played breaking ties)").". "; 
			}
			


			if ($specialdeck==1 && strpos($playpara, "irrespective"))
			{ $playpara .= "For the major arcana, the cards rank equal to their number (such that Temperance equals a King, etc.)."; }

			$playpara .= "The ".randword("winner of the trick","winner of the trick","player to the left of the previous leader","player of the lowest ranked card (breaking ties by first card played)")." leads the next trick.";

			$rules .= paragraph($playpara);

			if ($kitty != "") { $rules .= paragraph("The first time a player wins a trick which contains ".randword("a $badsuit",cardname($deck[array_rand($deck)])." (the <b>".maleDiminutive($specialdeck)."</b>)").", they also take the $kitty as if it were a separate, won trick."); }
		}

		$cardssetaside = 0;
		$tookastrick = 0;
		if ($gametype == CLIMBING_GAME)
		{
			$sets = 0; $runs = 0;
			if ($numberoftricks == -1 && rand(1,$rarity)==1 && $therewillberuns==0)
			{
				$playpara .= "Whoever has ".cardname($deck[array_rand($deck)])." plays it to lead the first trick.";
			}
			else
			{
				if ($landlord != "")
				{ $playpara .= "The $landlord"; }
				else
				{ $playpara .= randword("The dealer","The player to the dealer's left"); }
				$playpara .= " ".randword("starts by leading","leads","may lead")." ";
				
				if (rand(1,$rarity*2)==1 || $shithead==1)
				{ $playpara .= "any single card."; }
				elseif (rand(1,3)>1)
				{
					$runstart = rand(0,sizeof($ranks)-3);
					$sets = 1; $runs = 1;
					$playpara .= "any single card, or set of cards of equal rank (eg. ".randword("a pair of","three")." ".rankword($ranks[rand(0,count($ranks)-1)])."s),
								  or run of three or more cards ";
								  
					if (rand(1,4)==1)
					{
						$firstsuit = ""; $secondsuit = ""; $thirdsuit = "";
						while ($firstsuit == $secondsuit || $firstsuit == $thirdsuit || $thirdsuit == $secondsuit)
						{
							$firstsuit = randsuit($specialdeck);
							$secondsuit = randsuit($specialdeck);
							$thirdsuit = randsuit($specialdeck);
						}
						$playpara .= "across any suits (eg. ".rankword($ranks[$runstart+2])." of ".$firstsuit."s, ".rankword($ranks[$runstart+1])." of ".$secondsuit."s, ".rankword($ranks[$runstart])." of ".$thirdsuit."s).";
					}
					else
					{ $playpara .= "of the same suit (eg. ".rankword($ranks[$runstart+2])."-".rankword($ranks[$runstart+1])."-".rankword($ranks[$runstart])." of ".randsuit($specialdeck)."s)."; }
				}
				else
				{ $sets = 1; $playpara .= "any single card, or set of cards of equal rank (eg. ".randword("a pair of","three")." ".rankword($ranks[rand(0,count($ranks)-1)])."s)."; }
			}

			$rules .= paragraph($playpara);
			$playpara = "Each player in turn must either pass or play a card ";
			if ($sets==1) { $playpara .= "or set of cards "; }			
			$playpara .= "which beats the previous play.";

			if (rand(1,$rarity*2)==1) { $playpara .= "(A player <i>must</i> play if they are able to do so.)"; }
			
			$singlebomb = 0;
			if (rand(1,$rarity)==1) { $singlebomb = 1; }

			if (rand(1,$rarity)==1)
			{
				$playpara .= "A single card is beaten by a higher single card, ";
				if ($runs == 1) { $playpara .= "by any run, "; }
				$playpara .= "or by any pair, triple or quartet set (even if the set's rank is lower).";
				$playpara .= "A set is beaten by a set of the same size with higher ranks, or a larger set of any rank.";
				$example = rand(0,sizeof($ranks)-3); $off = rand(1,2);
				$playpara .= "(For example, ".rankword($ranks[$example])."-".rankword($ranks[$example])." can be beaten by ".rankword($ranks[$example+$off])."-".rankword($ranks[$example+$off])."-".rankword($ranks[$example+$off]).".)";
				$sets = 1; 
			}
			elseif (rand(1,$rarity)==1)
			{
				$playpara .= "A single card is beaten by a higher single card, ";
				if ($runs == 1) { $playpara .= "by any run that includes a higher ranked card, "; }				
				$playpara .= "or by any set of cards";
				if ($sets == 0) { $playpara .= " (eg. a ".randword("pair of","set of three")." ".rankword($ranks[rand(0,count($ranks)-1)])."s)"; }
				$playpara .= " of higher rank.";
				$playpara .= "A set is beaten by a set of the same size with higher ranks, or a larger set of a higher rank.";
				$example = rand(0,sizeof($ranks)-3); $off = rand(1,2);
				$playpara .= "(For example, a ".rankword($ranks[$example])." can be beaten by a ".rankword($ranks[$example+$off])."-".rankword($ranks[$example+$off]).".)";
				$sets = 1; 
			}
			else
			{
				if ($sets==1)
				{
					$playpara .= "A single card is beaten by a higher single card";
					$playpara .= ", and a set is beaten by a set of the same size with a higher card rank.";
					//if ($singlebomb == 0) { $playpara .= "(A set cannot be played onto a single card, or vice versa.)"; }
					//else { $playpara .= "(A set cannot be played onto a single card, and a single card cannot be played onto a set it doesn't beat.)"; }
				}
				else
				{ $playpara .= "A card is beaten by any card of a higher rank."; }
			}
			
			if ($runs == 1) { $playpara .= "Runs are beaten by runs of the same size, starting at a higher rank."; }

			$rules .= paragraph($playpara);
			$playpara = "";

			if (in_array("1J",$deck))
			{		
				if (rand(1,$rarity)==1)
				{ $playpara .= "A single joker can be played at any time, and beats any other play."; }
				else
				{
					if (in_array("2J",$deck)) { $playpara .= "The jokers are "; }
					else { $playpara .= "The joker is ";}
					$playpara .= "wild and can be played as any card.";
					if (rand(1,$rarity)==1 && $sets==1)
					{
						$example = rankword($ranks[rand(0,count($ranks)-1)]);
						$playpara .= "A set with a joker can be beaten by the same natural version of the set (eg. $example-joker is beaten by $example-$example).";
					}
				}
			}

			if ($singlebomb == 1)
			{ $playpara .= "A single ".rankword($ranks[sizeof($ranks)-1])." can be played at any time, and beats any other play."; }
			elseif (rand(1,$rarity)==1 && $specialdeck==0)
			{ $playpara .= "A single card can be beaten by a card of the same rank in the opposite colour suit."; }

			if (rand(1,$rarity)==1 && $singlebomb==0)
			{
				$bomb = randword("bomb","shell","petard","trap","snare","bolt","dart","dagger");
				if (rand(1,4)==1 && in_array("2J",$deck))
				{
					$rocket = randword("rocket","missile","firework","torpedo","fireball");
					$playpara .= "Four cards of the same rank are a <b>$bomb</b>, and can be played at any time to beat anything except a $rocket or a higher-ranking $bomb.";
					$playpara .= "A pair of jokers is a <b>$rocket</b>, which beats all other plays and cannot be beaten.";
				}
				else
				{
					$playpara .= "Four cards of the same rank are a <b>$bomb</b>, and can be played at any time to beat anything except a higher-ranking $bomb.";
				}
				
				array_push($facts,"powerful \"".$bomb."s\" can defeat weaker plays");
			}
			
			if (rand(1,$rarity)==1)
			{
				$glass = $ranks[rand(0,count($ranks)-1)];
				$playpara .= ucfirst(numberword(rankword($glass)))."s are <b>".randword("transparent","invisible","glass","clear","crystal","ghosts")."</b>: after you play a ".rankword($glass);
				
				if ($sets == 1) { $playpara .= " or a set of ".rankword($glass)."s, they are counted as copies of the card beneath them."; }
				else { $playpara .= ", it is counted as a copy of the card beneath it."; } 
				
				$playpara .= "If there is no card beneath it, it counts as a ".rankword($glass).".";
			}

			$rules .= paragraph($playpara);

			if ($maorule != "")
			{
				$rule = "rule";
				if (preg_match("/^[a-z]+ ([a-z]+)$/",$maorule,$matches)) { $rule = $matches[1]; }

				$example = randword("that card being unbeatable","changing the play order","skipping the next player","forcing a player to draw cards","swapping cards in play","eliminating a player from the hand");
				
				if ($bartok == 1)
				{
					$playpara = "If the ".$maorule."s mean that a player has no valid play open to them, they must pass.";
					$rules .= paragraph($playpara);
				}
				else
				{
					$playpara = "Players must obey ".$maorule."s. If a player breaks one without realising, the player who invented the $rule informs them that they have done so (without explaining the rule): the ".$rule."-breaker must take back their play";
					$playpara .= ", draw one penalty card";
					$playpara .= " and play again. If no play is possible, they may pass.";
					$playpara .= "If a $maorule causes an effect to occur when a card is played (such as $example), the ".$rule."'s inventor informs the players of this when it happens.";
					$rules .= paragraph($playpara);
				}
			}
			
			$playpara = "";
			
			if (rand(1,2)==1 && $sets == 0) // Skitgubbe
			{
				$playpara = "The hand continues until a player passes, or until there are as many cards in the middle as there are players.";
				array_push($adjectives,randword("high-pressure"));
			}
			else
			{
				if ($minplayers == 2)
				{
					$playpara .= "In a two-player game, the hand continues until a player passes.";
					
					if ($maxplayers == 3) { $playpara .= "With three, it"; } else { $playpara .= "With more, it"; }
					
				}
				else
				{ $playpara .= "The hand"; }
				
				$playpara .= " continues until all but one player has passed in sequence.";
					
				if (rand(1,$rarity)==1) { $playpara .= "(Once a player has passed during a trick they cannot rejoin it.)"; }
				else { $playpara .= "(Otherwise, a player that has passed may still play a card later in the trick.)"; }
			}
				
			if (rand(1,2)==1)
			{
				$playpara .= "When this happens, the player who played the final card takes the pile of played cards as a trick, and leads the next.";
				if ($kitty != "") { $rules .= paragraph("The first time a player wins a trick which contains ".randword("a $badsuit",cardname($deck[array_rand($deck)])." (the <b>".maleDiminutive($specialdeck)."</b>)").", they also take the $kitty as if it were a separate, won trick."); }
				$tookastrick = 1;
			}
			else
			{
				$cardssetaside = 1;
				$playpara .= "When the hand ends, the pile of played cards is set aside face-down and the player who played the final card to it leads the next trick.";
				if ($kitty != "") { $playpara .= "The first time that ".randword("a $badsuit",cardname($deck[array_rand($deck)])." (the <b>".maleDiminutive($specialdeck)."</b>)")." is in the cards set aside, the player takes the $kitty into their hand."; }
			}
			$rules .= paragraph($playpara);
			$playpara = "";
		}
		
		$packterm = "";
		if ($gametype == MELDING_GAME)
		{
			$playpara = "";
			$playpara .= "The ".randword("dealer","player to the dealer's left")." takes the first turn.";
			
			$machiavelli = 0;
			if (rand(1,$rarity)==1)
			{ $machiavelli = 1; $playpara .= "On their turn a player may take <i>one</i> of the following actions:</p><ul>"; }
			else
			{ $playpara .= "A player's turn consists of the following steps in sequence:</p><ul>"; }

			if (rand(1,$rarity)==1)
			{
				$packterm = randword("desert","ditch","sink","load","stow","drop","slip","bundle","flop");
				
				if ($machiavelli == 1)
				{ $playpara .= "<li><b>".ucfirst($packterm)."</b>"; }
				else
				{ $playpara .= "<li>Optionally <b>$packterm</b>"; }

				$playpara .= ": shuffle a third of your hand (of your choice, and rounding down) into the stock; if this is your first turn you shuffle a half of your hand instead. A player who has ".$packterm."ed holds onto their remaining cards but takes no further part in the round.";
				$playpara = preg_replace("/bundleed/","bundled",$playpara);
				$playpara = preg_replace("/sinked/","sunk",$playpara);
				$playpara = preg_replace("/(dro|sli|flo)ped/","$1pped",$playpara);
				
				array_push($facts,"players can opt to drop out early");
				array_push($adjectives,randword("tactical","skilful","thoughtful"));
			}			
			
			$wipe=0;
				
			$playpara .= "<li><b>Draw</b> ";
			if (rand(1,$rarity)==1)
			{
				$playpara .= randword("two","one or two")." cards from the top of the stock";
				if ($kitty != "") { $playpara .= ", one card from the top of the the $kitty,"; }
			}
			else
			{
				$playpara .= "one card from the top of the stock";
				if ($kitty != "") { $playpara .= " or the $kitty,"; }
			}
			if (rand(1,$rarity)==1)
			{ $wipe=1; $playpara .= " or any number of cards from the top of the discard pile"; }
			else
			{ $playpara .= " or one card from the top of the discard pile"; }
			if (rand(1,$rarity)==1) { $playpara .= ". A player who has not yet played a meld may not draw from the discard pile"; }
			if (rand(1,$rarity)==1 && $machiavelli==0)
			{
				$playpara .= ". If the player draws from the discard pile, they must play ";
				if ($wipe==1) { $playpara .= "all of those cards"; } else { $playpara .= "that card"; }
				$playpara .= " into a meld this turn";
			}
			$playpara .= ".</li>";

			if ($machiavelli == 1)
			{ $playpara .= "<li>Play "; }
			else
			{ $playpara .= "<li>Optionally play "; }

			$playpara .= "".randword("one <b>meld</b>","one or more <b>melds</b>")." from your hand";
			if ($machiavelli == 0) { $playpara .= ", if you can do so"; }
			$playpara .= ", placing your melds face-up on the table in front of you. A meld ";
			$playpara .= "can either be three or more cards of equal rank (eg. ".randword("four","three")." ".rankword($ranks[rand(0,count($ranks)-1)])."s),
						  or run of three or more cards ";					  
			$runstart = rand(0,sizeof($ranks)-3);
			if (rand(1,4)==1)
			{
				$firstsuit = ""; $secondsuit = ""; $thirdsuit = "";
				while ($firstsuit == $secondsuit || $firstsuit == $thirdsuit || $thirdsuit == $secondsuit)
				{
					$firstsuit = randsuit($specialdeck);
					$secondsuit = randsuit($specialdeck);
					$thirdsuit = randsuit($specialdeck);
				}
				$playpara .= "across any suits (eg. ".rankword($ranks[$runstart+2])." of ".$firstsuit."s, ".rankword($ranks[$runstart+1])." of ".$secondsuit."s, ".rankword($ranks[$runstart])." of ".$thirdsuit."s).";
			}
			else
			{ $playpara .= "of the same suit (eg. ".rankword($ranks[$runstart+2])."-".rankword($ranks[$runstart+1])."-".rankword($ranks[$runstart])." of ".randsuit($specialdeck)."s)."; }
			if (rand(1,$rarity)==1) {  $playpara .= "A single ".rankword($ranks[rand(0,count($ranks)-1)])." by itself is also a valid meld."; }

			if (rand(1,$rarity)==1)
			{
				$playpara .= "Each player's first meld of the round must be a run";
				if (in_array("1J",$deck) || $wildmeld==1) { $playpara .= " containing no jokers"; }
				$playpara .= ".";
			}
			if (rand(1,$rarity)==1) {  $playpara .= "A player is not permitted to meld all of their cards on their first turn."; }
			elseif (rand(1,$rarity)==1) {  $playpara .= "If you have any full and valid melds in your hand you <i>must</i> play one of them."; }
			
			$keepcard=0;
			$rearrangemelds=0;
			if (rand(1,$rarity)==1 && $machiavelli==0) { $keepcard=1;  $playpara .= "You can never have an empty hand after playing a meld: you must keep one card back to discard at the end of the turn."; }
			if (rand(1,$rarity)==1)
			{
				$rearrangemelds = 1;
				$playpara .= "You may break up and rearrange ";
				if (rand(1,$rarity)==1)
				{ $playpara .= "your own melds"; }
				else
				{ $playpara .= "any melds on the table"; }
				$playpara .= " when playing a new meld, so long as you are adding at least one card to the table and leave the table as a collection of valid melds.";
				
				array_push($facts,"melds can be rearranged");
				array_push($adjectives,randword("complex","expansive","convoluted","elaborate","labyrinthine","tangled","byzantine"));
			}
			
			$playpara .= "</li>";

			if ($rearrangemelds==0)
			{				
				$playpara .= "<li><b>Lay off</b> any number of cards, by adding them to ".randword("any player's meld already on the table","any player's meld already on the table","one of your own melds").". The meld laid off to must remain a valid meld";
				if ($keepcard==1) { $playpara .= " and you cannot lay off the last card in your hand in this way"; }
				$playpara .= ".</li>";
			}

			if (rand(1,$rarity)==1 && $rearrangemelds==0)
			{				
				$playpara .= "<li><b>".randword("Dump","Scrap","Shed","Chuck","Junk","Unload","Slough")."</b> any melds on the table which consists of four cards of equal rank";
				if (in_array("1J",$deck) || $wildmeld==1) { $playpara .= " (and no jokers)"; }
				$playpara .= ", placing them on the discard pile.</li>";
			}
			
			if (rand(1,$rarity)==1)
			{				
				if ($machiavelli == 1)
				{ $playpara .= "<li>Discard"; }
				else
				{ $playpara .= "<li>Optionally discard"; }
				$playpara .= " a <b>".randword("stump","vault","chest","sphinx","cipher","enigma","crux")."</b> from your hand: these are four cards of the same rank.";
				if (in_array("1J",$deck) || $wildmeld==1) { $playpara .= " (This cannot include any jokers.)"; }
				$playpara .= "These cards are placed face-down to one side, and take no further part in the round.</li>";
			}
			
			if ($machiavelli == 0)
			{
				$playpara .= "<li><b>Discard</b> one card to the discard pile.";
				if ($wipe==1) {  $playpara .= " Discards are made into an overlapping row so that all of their values can be seen."; }
				if (rand(1,$rarity)==1)
				{
					$shanghai = randword("buried","decoy","waylay","captured","hijack","skyjack","hands off");
					$playpara .= " If the discarded card could be played to a meld, you may call <b>\"$shanghai\"</b> as you discard it; if you do so, the next player may not draw that card. (If you call $shanghai in error, draw a random penalty card from the hand of the player who points out your error.)";
				}
			}
			$playpara .= "</li>";

			$playpara .= "</ul><p>";

			if (in_array("1J",$deck) || $wildmeld==1)
			{		
				if (in_array("2J",$deck) || $wildmeld==1) { $playpara .= "The jokers are "; }
				else { $playpara .= "The joker is ";}
				$playpara .= "wild and can be played as any card.";
				if (rand(1,$rarity)==1 && $wildmeld==0) { $playpara .= "A player may swap a card in their hand for a joker in a meld, during their turn, if the meld remains valid."; }
				if (rand(1,$rarity)==1) { $playpara .= "A meld may not contain more than one joker."; }
				if ($rearrangemelds==1) { $playpara .= "The value of a joker may be changed when rearranging melds."; }
			}
			elseif (rand(1,$rarity)==1 && $wildmeld==0)
			{
				$playpara .= "In the first hand ".rankword($ranks[sizeof($ranks)-1])."s are wild and can be played as any card.
				In the second hand ".rankword($ranks[sizeof($ranks)-2])."s are wild, in the third ".rankword($ranks[sizeof($ranks)-3])."s, and so on.";
			}
			
			$rules .= paragraph($playpara);
			$playpara = "";

			$playpara .= "If the stock runs out, shuffle ";
			if ($seconddiscard == 1)
			{ $playpara .= "both discard piles"; }
			else
			{ $playpara .= "the discard pile"; }
			$playpara .= " to form a new stock.";
			
			$rules .= paragraph($playpara);
			
			if (rand(1,$rarity)==1 && $rearrangemelds==0) { $rules .= paragraph("If a player begins to rearrange melds but is unable to complete them, they must take back the cards they played, plus an additional ".randword("three","four")." penalty cards."); }

			$firstrank = ""; $secondrank = "";
			while ($firstrank == $secondrank)
			{
				$firstrank = rankword($ranks[rand(0,count($ranks)-1)]);
				$secondrank = rankword($ranks[rand(0,count($ranks)-1)]);
			}
			
			if (rand(1,$rarity*2)==1 && $handsize<7)
			{ $rules .= paragraph("Players may also form a <b>".randword("full house","absolute","brink","ultimate","apex","nonpareil","pinnacle")."</b> meld, consisting of three of
			one rank and two of another (eg. $firstrank-$firstrank-$firstrank-$secondrank-$secondrank). Upon doing so they immediately remove
			that meld from the game and discard any cards left in their hand."); 
			
				array_push($facts,"a full house wins the game");
			}

			$playpara = "";
			
			if ($maorule != "")
			{
				$rule = "rule";
				if (preg_match("/^[a-z]+ ([a-z]+)$/",$maorule,$matches)) { $rule = $matches[1]; }
				
				if ($bartok == 1 && $machiavelli == 1)
				{ $rules .= paragraph("If the ".$maorule."s mean that a player has no valid play open to them, they must pass their turn."); }
				elseif ($bartok == 1)
				{ $rules .= paragraph("If the ".$maorule."s mean that a player cannot complete a step of their turn, they skip that step."); }
				else
				{
					$example = randword("destroying a meld","changing the play order","skipping the next player","forcing a player to draw cards");
					
					$playpara = "Players must obey ".$maorule."s. If a player breaks one without realising, the player who invented the $rule informs them that they have done so (without explaining the rule): the ".$rule."-breaker must take back their play";
					$playpara .= ", draw one penalty card";
					$playpara .= " and play again. If no play is possible, they may pass their turn.";
					$playpara .= "If a $maorule causes an effect to occur when a card is melded or discarded (such as $example), the ".$rule."'s inventor informs the players of this when it happens.";
					$rules .= paragraph($playpara);
				}
			}
		}

		if ($gametype == MATCHING_GAME)
		{
			$remainingranks = $ranks;
			shuffle($remainingranks);
			$playpara = "";
			$playpara .= "The ".randword("dealer","player to the dealer's left")." takes the first turn.";
			$rules .= paragraph($playpara);
			$playpara = "";
			
			if ($cheat==1)
			{
				$playpara .= "Each player in turn plays a card to the discard pile <i>face-down</i>, announcing what they are claiming the card to be. The player may (and if no legal play is available to them <i>must</i>) lie about this.";
				$playpara .= "The card a player claims to be playing must match ";
			}
			else
			{
				$playpara .= "Each player in turn must play a legal card to the discard pile, if they are able to do so.";
				$playpara .= "A card must ";
			}

			$reversephrase = "";
			$keeprising = 0;

			$examplerank = rankword(array_pop($remainingranks));
			$examplesuit = randsuit($specialdeck);
			$playpara .= "match the suit or rank of the top card of ";			
			if ($seconddiscard == 1) { $playpara .= "whichever discard pile it is being played onto"; }
			else { $playpara .= "the discard pile"; }
			$playpara .= " (for example, the $examplerank of ".$examplesuit."s can be followed by a $examplerank of any suit, or any $examplesuit).";

			$powers = array();
			$powerrarity = 5;
			$rules .= paragraph($playpara);
			$playpara = "";
			
			if (rand(1,$powerrarity)==1)
			{
				$draw = array_pop($remainingranks);
				$card = rankword($draw);
				$height = rankheight($draw);
				if (rand(1,2)==1)
				{
					$powers[$height] = "".randword($card."s are wild.","A $card is wild, and can always be played. ")."When you play a $card, you may name a suit: the $card is considered to have that suit instead of its usual one.";
				}
				else
				{
					$powers[$height] = "A $card can always be played, and any card may be played onto a $card.";
				}
				if (rand(1,$rarity)==1 && preg_match("/always be played/",$powers[$height]))
				{
					$powers[$height] = "You can't play a $card if it's the only card left in your hand.";
				}
			}
			if ($kitty != "" && sizeof($remainingranks)>0)
			{
				$draw = array_pop($remainingranks);
				$card = rankword($draw);
				$height = rankheight($draw);
				if (rand(1,2)==1) { $powers[$height] = "The first person to play a $card must take the $kitty into their hand."; }
				else { $powers[$height] = "The first time a $card is played, whoever played it may force the next player to take the $kitty into their hand."; }
			}
			if (rand(1,$powerrarity)==1)
			{
				$draw = array_pop($remainingranks);
				$card = rankword($draw);
				$height = rankheight($draw);
				$powers[$height] = "".randword("A $card can always be played; after doing so,","After playing a $card,")." shuffle the discard pile into the stock, and the player who played the $card takes another turn.";
				if (rand(1,$rarity)==1 && preg_match("/always be played/",$powers[$height]))
				{
					$powers[$height] = "You can't play a $card if it's the only card left in your hand.";
				}
			}
			if (rand(1,$powerrarity)==1 && sizeof($remainingranks)>0)
			{
				$reversephrase = " (or right, if play is reversed)";
				$draw = array_pop($remainingranks);
				$card = rankword($draw);
				$height = rankheight($draw);
				$powers[$height] = "Playing a $card reverses the direction of play.";
			}
			if (rand(1,$powerrarity)==1 && sizeof($remainingranks)>0)
			{
				$draw = array_pop($remainingranks);
				$card = rankword($draw);
				$height = rankheight($draw);
				if (rand(1,4)==1)
				{ $powers[$height] = "After playing a $card, select any player. They must either draw two cards from the stock, or play a $card from their own hand and select any player; if they select a player, that player must either draw <i>three</i> cards or play a $card, and so on. Normal play then continues from the original player's left$reversephrase."; }
				else
				{ $powers[$height] = "When a $card is played, the next player must play a $card or draw two cards from the stock. If a second $card is played the next player must play a $card or draw four cards, and so on."; }
			}
			if (rand(1,$powerrarity)==1 && sizeof($remainingranks)>0)
			{
				$draw = array_pop($remainingranks);
				$card = rankword($draw);
				$height = rankheight($draw);
				$powers[$height] = "If you play a $card, take another turn.";
			}
			if (rand(1,$powerrarity)==1 && sizeof($remainingranks)>0)
			{
				$draw = array_pop($remainingranks);
				$card = rankword($draw);
				$height = rankheight($draw);
				$powers[$height] = "If you play a $card, the next player skips their turn.";
			}
			if (rand(1,$powerrarity)==1 && sizeof($remainingranks)>0)
			{
				$draw = array_pop($remainingranks);
				$card = rankword($draw);
				$height = rankheight($draw);
				$powers[$height] = "When anyone plays a $card, every player (going clockwise from your left) must draw one card.";
			}
			if (rand(1,$powerrarity)==1 && sizeof($remainingranks)>0)
			{
				$draw = array_pop($remainingranks);
				$card = rankword($draw);
				$height = rankheight($draw);
				$powers[$height] = "A $card can only be followed by a ".rankword($ranks[0]).", ".rankword($ranks[1])." or ".rankword($ranks[2]).", and does not have to follow suit.";
			}
			if (rand(1,$powerrarity)==1 && sizeof($remainingranks)>0)
			{
				$draw = array_pop($remainingranks);
				$card = rankword($draw);
				$height = rankheight($draw);
				$powers[$height] = "After a $card is played, everyone passes their hand to the ".randword("right","left").".";
			}
			if (rand(1,$powerrarity*2)==1)
			{
				$specificcard = cardname($deck[array_rand($deck)]);
				$powers[-2] = "If you play $specificcard (the <b>".maleDiminutive()."</b>) you must draw ".numberword("five","six","seven","four","three")." cards.";
			}
			elseif (rand(1,$powerrarity*2)==1)
			{
				$specificcard = cardname($deck[array_rand($deck)]);
				$powers[$specificcard] = "If you play $specificcard (the <b>".maleDiminutive()."</b>) the next player must draw ".numberword("five","six","seven","four","three")." cards.";
			}
			if (rand(1,$powerrarity)==1 && sizeof($remainingranks)>0)
			{
				$draw = array_pop($remainingranks);
				$card = rankword($draw);
				$height = rankheight($draw);
				$powers[$height] = "If you play a $card, you may give one card from your hand to an opponent.";
			}
			if (rand(1,$powerrarity)==1 && sizeof($remainingranks)>0)
			{
				$draw = array_pop($remainingranks);
				$card = rankword($draw);
				$height = rankheight($draw);
				$powers[$height] = ucfirst(numberword($card))."s are ".randword("transparent","invisible","glass","clear","crystal","ghosts").": after playing one, put it on the bottom of the discard pile.";
			}
			if (rand(1,$powerrarity)==1 && sizeof($remainingranks)>0)
			{
				$draw = array_pop($remainingranks);
				$card = rankword($draw);
				$height = rankheight($draw);
				$powers[$height] = "If you play a $card, you may tuck any other card from your hand underneath it.";
			}
			if (rand(1,$powerrarity)==1 && sizeof($remainingranks)>0)
			{
				$draw = array_pop($remainingranks);
				$card = rankword($draw);
				$height = rankheight($draw);
				$powers[$height] = ucfirst($card)."s are ".randword("cordial","decent","goodwill","hospitality","charity","kindness","geneous")." cards: when playing one, you must name a suit other than the one it has. The next player has the option of playing the suit you named, in addition to the $card's suit.";
			}
			if (rand(1,$powerrarity)==1 && sizeof($remainingranks)>0)
			{
				$draw = array_pop($remainingranks);
				$card = rankword($draw);
				$height = rankheight($draw);
				$powers[$height] = "After you play a $card, you may optionally trade your hand with any other player.";
			}
			if (rand(1,$powerrarity)==1 && sizeof($remainingranks)>0)
			{
				$draw = array_pop($remainingranks);
				$card = rankword($draw);
				$height = rankheight($draw);
				$powers[$height] = "If you play a $card, name any card rank: the next player must either play that card rank or pass, on their turn.";
			}
			if (rand(1,$powerrarity)==1 && sizeof($remainingranks)>0)
			{
				$draw = array_pop($remainingranks);
				$card = rankword($draw);
				$height = rankheight($draw);
				$powers[$height] = "If you play a $card, you must either place another card of the same suit on top of it, or draw a card.";
			}
			if (rand(1,$powerrarity)==1 && sizeof($remainingranks)>0)
			{
				$draw = array_pop($remainingranks);
				$card = rankword($draw);
				$height = rankheight($draw);
				$powers[$height] = "If you play a $card onto another card which had a special effect, it repeats that effect as if it were a copy of that card. (It remains a $card.)";
			}
			if (rand(1,$powerrarity)==1 && sizeof($remainingranks)>0)
			{
				$draw = array_pop($remainingranks);
				$card = rankword($draw);
				$height = rankheight($draw);
				if ($countdown==1)
				{ $powers[$height] = "If you play a $card, you may immediately discard 1 ".$currency."."; }
				else
				{
					$powers[$height] = "If you play a $card, you immediately gain ".rand(3,6)." points.";
					$positivepoints = 1;
				}
				array_push($facts,"points can be scored during the hand");
			}
			if (rand(1,$powerrarity)==1 && sizeof($remainingranks)>0)
			{
				$draw = array_pop($remainingranks);
				$card = rankword($draw);
				$height = rankheight($draw);
				$powers[$height] = "If a $card is played, the next card must be ".randword("lower than","higher than")." or equal to a $card.";
			}

			if (in_array("1J",$deck))
			{		
				$jpara = "";
				if (in_array("2J",$deck)) { $jpara .= "The jokers are "; }
				else { $jpara .= "The joker is ";}
				$jpara .= "wild and can be played as any card.";
				$powers["-1"] = $jpara;
			}
			if ($countdown==1 && rand(1,3)==1)
			{
				$powers["999"] = "The number of ".$currency."s a player has determines which rank of card is \"wild\" for them: four ".$currency."s means that 4s are wild, etc. ".randword("When a wild card is played, the player","A player can play a wild onto any card, and when playing it they")." may name a suit: the card is considered to have that suit instead of its usual one. (If a wild card also has an effect as listed below, it also occurs.)";
			}
		
			if (sizeof($powers)>0)
			{
				if (sizeof($powers)==1)
				{
					if ($cheat==1)
					{ $playpara .= "One card has a special effect when a player claims to play it. That is:-</p><ul>"; }
					else
					{ $playpara .= "One card has a special effect when played. That is:-</p><ul>"; }
				}
				else
				{
					if ($cheat==1)
					{ $playpara .= "Some cards may have special effects when a player claims to play them. These are as follows:-</p><ul>"; }
					else
					{ $playpara .= "Some cards may have special effects when played. These are as follows:-</p><ul>"; }
				}
			
				krsort($powers);
				foreach ($powers as $key => $value)
				{
					$playpara .= "<li>$value</li>";
				}
				$playpara .= "</ul><p>";
			}
			
			if ($cheat==0)
			{
				if (rand(1,$rarity*2)==1)
				{ $playpara .= "A player <i>must</i> play a card each turn if they can. If they cannot, "; }
				else
				{ $playpara .= "If a player cannot or does not want to play a card, "; }
 
				if (rand(1,$powerrarity)==1 || $keeprising == 1) // Shithead
				{ $playpara .= "they pass their turn and draw the discard pile into their hand."; }
				else
				{ $playpara .= "they pass their turn and draw a card from the stock."; }
			
				if (rand(1,$rarity*2)==1)
				{ $playpara .= "If the drawn card could be played, they may play it immediately."; }
				
				$playpara .= "If the stock runs out, shuffle ";
				if ($seconddiscard == 1)
				{ $playpara .= "all but the top cards of each discard pile"; }
				else
				{ $playpara .= "all but the top card of the discard pile"; }
				$playpara .= " to form a new stock.";
			}
			$rules .= paragraph($playpara);

			if (rand(1,$rarity)==1)
			{ $rules .= paragraph("If a player has several cards of the same rank, all of which are a legal play, they may play them together as a single play. The top card determines the suit to follow, and if the cards have special effects only the top one occurs."); }
			elseif (rand(1,$rarity)==1)
			{ $rules .= paragraph("A player may play several cards together as a single play, if they are either all the same rank or all the same suit. The top card determines the suit to follow, and if the cards have special effects only the top one occurs."); }

			if ($cheat==1)
			{
				$theyplayed = "";
				if ($seconddiscard == 1) { $theyplayed = " they played onto"; }
				$playpara = "Any player may challenge a play they believe to be a lie, by shouting <b>\"".randword("bunco","flimflam","gyp","spoof","sting","fake","quack","rascal")."!\"</b> The play is revealed: if the player was lying they must pick up the discard pile$theyplayed into their hand, and if the challenger was mistaken, they must pick it up instead - either way play continues from the challenged player's left$reversephrase.
									 An unchallenged play continues with everyone acting as if the claimed card had truly been played.";
				if (sizeof($powers)>0) { $playpara .= "If the claimed card has a special ability and is unchallenged, its ability occurs."; }
				$rules .= paragraph($playpara);
			}

			if (sizeof($powers)>0)
			{ 
				$playpara = "If a card with a special effect is turned up as the first card of the discard pile, it ".randword("is treated as if the dealer had played it","has no effect").".";
				if (in_array("1J",$deck)) { $playpara .= "If it's a joker, the dealer decides its value before looking at their own cards."; }
				$rules .= paragraph($playpara); 
			}
			else if (in_array("1J",$deck)) { $playpara .= "If a joker is turned up as the first card of the discard pile, the dealer decides its value before looking at their own cards."; }
			
			$playpara = "";
		}

		if (rand(1,$rarity)==1 && ($gametype == MATCHING_GAME || $gametype == MELDING_GAME))
		{
			$shout = $gameName;
			if (preg_match("/^(['\p{L}]+)\b/",$shout,$matches))
			{ $shout = $matches[1]; }
			elseif (preg_match("/\b(['\p{L}]+)$/",$shout,$matches))
			{ $shout = $matches[1]; }
			$rules .= paragraph("If a player has only one card in their hand they must call \"<b>$shout</b>\". (If they fail to do before the next player starts their turn, they must draw a card.)");
		}

		if ($shithead==1)
		{
			$rules .= paragraph("If you have no cards in your hand, you may play cards from your face-up row (and if the face-up ones have been played, the face-down ones) as if they were in your hand. Face-down cards are played blind: if their play is invalid, you must put the card back where you played it from, and pass your turn.");
			array_push($facts,"the final cards of the game are played blind");
			array_push($adjectives,randword("luck-heavy","slapdash","messy","haphazard","clumsy","hit-or-miss"));
		}

		// SCORING
		$rules .= heading("Scoring");
		$losingpoints = 0;
		$president = "";
		$assholescore = rand(0,2);
		$playpara = "";

		$solongas = "";
		if ($tookastrick == 1) { $solongas = ", so long as they took at least one trick during the hand"; }

		if (($gametype == CLIMBING_GAME && $cardssetaside == 1) || $gametype == MELDING_GAME || $gametype == MATCHING_GAME)
		{		
			if ($countdown==1) // countdown scoring
			{
				if ($shithead == 1) { $playpara .= "The first player to play out their face-down cards"; }
				else { $playpara .= "The first player to empty their hand"; }
				$playpara .= " wins the round, and discards one of their ".$currency."s$solongas."; $goodhandscore = 0;
				if ($pig != "" && $tookastrick == 1) { $playpara .= "If ".$pigname." was revealed at the start of the hand, whoever took it also discards a $currency. (If the winner took it, they discard two ".$currency."s.)"; }
				elseif ($pig != "") { $playpara .= "If ".$pigname." was revealed at the start of the hand, they discard two."; }
				$rules .= paragraph($playpara);
				$playpara = "";				
			}
			elseif (rand(1,2)==1 || $positivepoints==1) 
			{
				// Award points based on cards in hands
				
				if (rand(1,$rarity*2)==1 && $shithead == 0)
				{
					$playpara .= "A player whose hand contains cards whose face values total 5 or less may <b>knock</b>, ending the round immediately with them as its winner$solongas.";
				}
				elseif (rand(1,$rarity)==1 && $gametype == MELDING_GAME && $kitty == "")
				{
					if ($shithead == 1) { $playpara .= "When a player has played out their face-down cards"; }
					else { $playpara .= "When a player empties their hand"; }
					
					$playpara .= ", they <b>knock</b>. Each other player may take one more turn, then the round ends with the knocking player as its winner$solongas.";
				}
				elseif ($shithead == 1)
				{ $playpara .= "The first player to play out their face-down cards wins the round$solongas."; }
				else
				{ $playpara .= "The first player to empty their hand wins the round$solongas."; }
			
				if (rand(1,$rarity*3)==1) { $playpara .= "(The second time that the stock is exhausted, the round ends immediately and the player with the smallest hand wins. If tied, the tied player whose turn is happening or would have happened soonest wins.)"; }

				if ($packterm != "")
				{
					$playpara .= " Alternatively, if all but one player has ".$packterm."ed, the hand ends immediately with the remaining player as the winner$solongas.";
					$playpara = preg_replace("/bundleed/","bundled",$playpara);
					$playpara = preg_replace("/sinked/","sunk",$playpara);
					$playpara = preg_replace("/(dro|sli|flo)ped/","$1pped",$playpara);
				}
				
				if ($tookastrick == 1) { $playpara .= " (If the winner failed to win any tricks, the round scores nothing and a new round begins.)"; }

				$pigscore = randword("5","10","15");
				
				if ($landlord != "")
				{
					$playpara .= "If this is the $landlord, each opponent must pay them a number of points equal to the amount of the bid;
					if the $landlord loses, then they must pay the bid amount to each opponent.";
					if ($bomb != "" && $rocket != "")
					{ $playpara .= "These payments are doubled for every $bomb or $rocket played during the round."; }
					elseif ($bomb != "")
					{ $playpara .= "These payments are doubled for every $bomb played during the round."; }
					
					if ($pig != "")
					{
						$playpara .= "If a player has $pigname in their hand, their payment is doubled"; 
						if ($bomb != "") { $playpara .= " again"; }
						$playpara .= ".";
					}

					$goodhandscore = 0;
				}
				elseif (rand(1,2)==1)
				{
					if (rand(1,4)==1 && $positivepoints==0)
					{ $losingpoints = 1; $playpara .= "Each other player loses points equal to the cards remaining in their own hand, as follows:</p><ul>"; }
					else
					{ $playpara .= "The winner gains points equal to the scores of the cards remaining in their opponents' hands, as follows:</p><ul>"; }

					$goodhandscore = 15;
					if ($wildmeld == 1 && in_array("1J",$deck)) { $j = rand(5,15); $playpara .= "<li> Jokers (including the rank declared as a joker this round) are worth $j</li>"; $goodhandscore += $j/15; }
					elseif ($wildmeld == 1) { $j = rand(5,15); $playpara .= "<li> Cards declared as a joker this round are worth $j</li>"; $goodhandscore += $j/15; }
					elseif (in_array("1J",$deck)) { $j = rand(5,15); $playpara .= "<li> Jokers are worth $j</li>"; $goodhandscore += $j/15; }
					if ($specialdeck == 1)
					{ 
						$j = randword("5","10","1","11","zero");
						if ($pigscore == $j || $pigscore+1 == $j) { $pigscore += 5; }
						$playpara .= "<li> Major arcana cards are worth $j</li>";
						$goodhandscore += 10;
					}
					if (in_array("KH",$deck) || in_array("QH",$deck) || in_array("Ks",$deck)) { $j=randword("5","10","12"); $playpara .= "<li> Face cards are worth $j</li>"; $goodhandscore += intval($j); }
					if (in_array("AH",$deck) || in_array("As",$deck)) { $j=randword("1","10","5"); $playpara .= "<li> Aces are worth $j</li>"; $goodhandscore += intval($j); }
					if ($pig != "") { $playpara .= "<li>If $pigname was revealed it is worth $pigscore; if unrevealed it scores as normal</li>"; }
										$playpara .= "<li> Number cards are worth ".randword("their ranked value","1 point each")."</li>";
					if ($shithead == 1) { $playpara .= "<li>Cards still in a player's face-up and face-down piles are worth 1 point each</li>"; }

					$playpara .= "</ul><p>";

				}
				elseif (rand(1,3)==1)
				{
					$playpara .= "Each other player loses points equal to the number of cards remaining in their own hand."; $goodhandscore = $handsize/2;
					if ($pig != "") { $playpara .= ucfirst($pigname)." counts as as it were ".randword("four","three","two","five")." cards for scoring."; }
					$losingpoints = 1;
					if ($shithead == 1) { $playpara .= "Cards still in a player's face-up and face-down piles are not counted for scoring."; }
				}
				else
				{ 
					$playpara .= "They gain points equal to the total number of cards remaining in their opponents' hands."; $goodhandscore = $maxplayers*2;
					if ($pig != "") { $playpara .= ucfirst($pigname)." counts as if it were ".randword("four","three","two","five")." cards for scoring."; }
					if ($shithead == 1) { $playpara .= "Cards still in a player's face-up and face-down piles are not counted for scoring."; }
				}

				if (rand(1,$rarity*3)==1)
				{
					$where = "play";
					if ($gametype == MELDING_GAME) { $where = "meld"; }
					$playpara .= "If the final $where of the round included only ".rankword($ranks[sizeof($ranks)-1])."s, these points are doubled.";
				}
				
				if ($gametype == MELDING_GAME && $pig != "")
				{ $playpara .= "If $pigname ended the game in a meld and a player revealed it at the start of the hand, that player gains $pigscore points."; }
			
				$rules .= paragraph($playpara);
				$playpara = "";				

			}
			elseif (rand(1,$rarity/2)==1)
			{
				// President-style scoring

				$firstscore = rand(2,3);
				$secondscore = rand(1,3);
				if ($firstscore == $secondscore) { $firstscore++; }
				$goodhandscore = ($firstscore+$secondscore)/2;

				$president = randword("leader","emperor","monarch","boss","magnate","majesty","mogul","imperator","chairman","wizard","magus");
				$vice = "";
				$asshole = randword("peasant","bumpkin","peon","serf","ninny","nincompoop","oaf","lummox","clod","rube","drudge","fool","jester","crook","toad","worm","slouch","slug","gutterpup","pit dweller");

				if ($shithead == 1) { $playpara .= "When a player has played out their face-down cards"; }
				else { $playpara .= "When a player empties their hand"; }

				if (rand(1,3)==1)
				{
					$playpara .= ", they win and become <b>".$president."</b>, scoring ".$firstscore." points";
					if ($kitty != "") { $playpara .= ", plus one point for every card (if any) left in the $kitty."; }
					$playpara .= ".";

					if ($shithead == 1) { $playpara .= "The single player (if any) with the most cards left (in both their hand and their face-up/face-down piles)"; }
					else { $playpara .= "The single player (if any) with the most cards left in their hand"; }
					$playpara .= " is the <b>".$asshole."</b>";
					if ($assholescore > 0)
					{ $playpara .= ", who loses ".$assholescore." points"; }
					$playpara .= ".";
				}
				else
				{
					$playpara .= ", they retire.";
				
					if ($packterm != "")
					{ $playpara .= "Continue playing until all but one player has either retired or ".$packterm."ed."; }
					else { $playpara .= "Continue playing until one player is left."; }
				
					$playpara .= "The first player to retire is the <b>".$president."</b> and scores ".$firstscore." points";
					if ($kitty != "") { $playpara .= ", plus one point for every card (if any) left in the $kitty."; }
					$playpara .= ".";
					if (rand(1,3)==1)
					{ 
						$vice = randword("advisor","aide","director","judge","priest","prince","manager","vizier","bishop","assistant","sheriff","witch");
						$playpara .= "The second player to retire is the <b>".$vice."</b> and scores ".$secondscore." points.";
					}

					if ($packterm != "")
					{ $playpara .= "The last player left, who neither retired nor ".$packterm."ed, "; }
					else
					{ $playpara .= "The last player left "; }
				
					$playpara .= "is the <b>".$asshole."</b>";
					if ($assholescore > 0)
					{ $playpara .= ", who loses ".$assholescore." points"; }
					$playpara .= ".";
				}

				if ($pig != "") { $playpara .= "If ".$pigname." was revealed at the start of the hand, the ".randword($president,$asshole)." ".randword("loses","scores")." ".rand(2,5)." points."; }

				$playpara = preg_replace("/bundleed/","bundled",$playpara);
				$playpara = preg_replace("/sinked/","sunk",$playpara);
				$playpara = preg_replace("/(dro|sli|flo)ped/","$1pped",$playpara);
					
				$rules .= paragraph($playpara);
				$playpara = "";

				if ($landlord != "")
				{
					$playpara .= "If the $landlord became the $president, each opponent must pay them a number of points equal to the amount of the bid;
					if they failed to become the $president then they must pay the bid amount to each opponent.";
					if ($bomb != "" && $rocket != "")
					{ $playpara .= "These payments are doubled for every $bomb or $rocket played during the round."; }
					elseif ($bomb != "")
					{ $playpara .= "These payments area doubled for every $bomb played during the round."; }
				}

				$rules .= paragraph($playpara);
				$playpara = "";
				
				array_push($facts,"the winners and losers are given titles");
				array_push($adjectives,randword("raucous","lively","fun","rowdy"));
			}
			else
			{
				// Basic scoring

				$playpara = "";
				if ($shithead == 1) { $playpara .= "The first player to play out their face-down cards"; }
				else { $playpara .= "The first player to empty their hand"; }
				$playpara .= " wins the round, and scores a point.";
				$goodhandscore = 1;
				
				if ($pig != "") { $playpara .= "If ".$pigname." was revealed at the start of the hand, they score an extra point."; $goodhandscore = 1.5; }
				
				$rules .= paragraph($playpara);
			}
			$playpara = "";
			
			if ($maorule != "")
			{
				if ($president != "" && $vice != "") { $winnerword = randword($president,$vice); }
				elseif ($president != "") { $winnerword = randword($president); }
				else { $winnerword = "winner of the round"; }
				if ($bartok==1)
				{
					$rules .= paragraph(randword("The $winnerword may invent a $maorule to apply to all future rounds. New ".$maorule."s exist alongside older ones.",
										 "The $winnerword may invent a $maorule to apply to all future rounds, or repeal an existing $maorule."));
				}
				else
				{
					$rules .= paragraph(randword("The $winnerword invents an additional $maorule, to apply to all future rounds.",
										 "The $winnerword may invent an additional $maorule, to apply to all future rounds, or repeal an existing $maorule (even if they are unsure of its exact phrasing)."));
				}
			}
		}
		elseif ($countdown==1) // countdown scoring
		{
			if ($shithead == 1) { $playpara .= "The first player to play out their face-down cards"; }
			else { $playpara .= "The first player to empty their hand"; }
			$playpara .= " wins the round, and discards one of their ".$currency."s."; $goodhandscore = 0;
			if ($pig != "") { $playpara .= "If ".$pigname." was revealed at the start of the hand, they discard two."; }
			$rules .= paragraph($playpara);
			$playpara = "";				
		}
		else
		{
			if ($gametype == CLIMBING_GAME) { $playpara = "When a player plays their last card, the round ends immediately (the final trick being discarded with no winner) and "; }
			else if ($numberoftricks == -1 || $boosted == 1) { $playpara = randword("When all tricks have been played")." "; }
			else { $playpara = randword("When all ".numberword($numberoftricks)." tricks have been played")." "; }

			$goodhandscore = 21;
			$president = ""; $asshole = "";
			if (rand(1,3)==1 && $bidding == 0 && $cardssetaside == 0) // Hearts-style negative points
			{
				$losingpoints = 1;
				if (rand(1,5)>1)
				{
					$playpara .= "each player loses 1 point per $badsuit taken in tricks";
					$goodhandscore = 10;
					array_push($facts,"players avoid taking ".$badsuit."s");
					array_push($adjectives,randword("classic","simple"));
				}
				else
				{
					$playpara .= "each player loses the face value of each $badsuit taken in tricks";
					$goodhandscore = 35;
					array_push($adjectives,randword("vindictive","cruel","spiteful","harsh"));
					array_push($facts,"players avoid taking $badsuit cards");
				}
				
				if (rand(1,4)==1)
				{
					$playpara .= " and loses an additional ".rand(7,13)." if they took ".cardname($deck[array_rand($deck)]);
					if (rand(1,3)==1) { $playpara .= " (the <b>".maleDiminutive($specialdeck)."</b>)"; }
					$playpara .= ".";
				}
				elseif (rand(1,4)==1)
				{
					$neddie = cardname($deck[array_rand($deck)]);
					$neddiename = maleDiminutive($specialdeck);
					$playpara .= " but gains ".rand(7,13)." if they took $neddie (the <b>$neddiename</b>).";
					
					if (preg_match("/$badsuit/",$neddie)) { $playpara .= " (The 1 point loss for ".$badsuit."s does not apply to the $neddiename itself.)"; }
				}
				else
				{ $playpara .= "."; }

				if (rand(1,7)==1 && in_array("1J",$deck)) { $playpara .= "Jokers ".randword("score a loss of")." ".rand(3,9)."."; }
				
				if ($pig != "") { $playpara .= ucfirst($pigname)." (if it was revealed at the start of the hand) ".randword("scores a further loss of","scores a positive")." ".numberword(rand(3,9))."."; }

				if (rand(1,$rarity)==1)
				{ $playpara .= "If one player took all of the ".$badsuit."s, they lose no points and instead gain ".rand(7,31)."."; }

				if (rand(1,4)==1)
				{
					$playpara .= "Taking ".cardname($deck[array_rand($deck)]);
					if (rand(1,3)==1) { $playpara .= " (the <b>".maleDiminutive($specialdeck)."</b>)"; }
					$playpara .= " doubles that player's score for the hand.";
				}
				
				if ($landlord != "")
				{ $playpara .= "If the $landlord took all or none all of the ".$badsuit."s during the hand, each opponent must pay the $landlord a number of points equal to the amount of the bid; otherwise the $landlord must pay the bid amount to each opponent."; }

			}
			elseif ($cardssetaside == 0)
			{
				if ($bidding == 1 && rand(1,$rarity)==1)
				{
					$playpara .= "each player who won the exact number of tricks they bid scores 10 plus that number; ";
					$playpara .= randword("all other players score zero","the other players score zero",
											"players who won fewer tricks than they bid score the number of tricks; players who won too many score zero",
											"players who won more tricks than they bid score the number of tricks; players who won too few score zero");
					$goodhandscore = 13;
				}	
				else
				{
					if ($numberoftricks>0)
					{ $playpara .= "each player scores ".randword("1 point per trick won beyond the ".nthword(rand(floor($numberoftricks*.4),floor($numberoftricks*.6))),"1 point per trick won"); $goodhandscore = $numberoftricks/2; }
					else
					{ $playpara .= "each player scores ".randword("1 point per trick won"); $goodhandscore = $handsize/2; }
				
					if ($bidding == 1)
					{
						$playpara .= ", plus a bonus 10 if they won the exact number of tricks they bid";
						$goodhandscore += 5;
						
						if (rand(1,$rarity)==1)
						{ $playpara .= " and an extra ".rand(1,5)." if that number was zero"; }
						if ($blindbid == 1)
						{ $playpara .= ". A successful blind bid earns a further ".rand(1,5)." points"; }							
					}
					$playpara .= ".";
				}

				if (in_array("1J",$deck) && rand(1,$rarity)==1)
				{ $playpara .= "Any trick with a joker in it is ".randword("discarded without scoring","worth an extra point")."."; }
				
				$playpara .= "."; 
				if ($pig != "") { $playpara .= "If $pigname was revealed, the player who won it ".randword("scores an extra point","scores an extra point","loses a point","scores nothing for this hand")."."; }

				if ($trumps == 1 && rand(1,$rarity)==1)
				{ $playpara .= "The player who won the highest trump card ".randword("scores an extra point","scores an extra point","loses a point")."."; }

				if ($bidding == 1 && rand(1,$rarity*2)==1)
				{ $playpara .= "If a player bids to win all the tricks of a hand and does so, they win the game instantly."; }

				if ($landlord != "")
				{ $playpara .= "If the $landlord won more tricks than any other player during the hand, each opponent must pay the $landlord a number of points equal to the amount of the bid; otherwise the $landlord must pay the bid amount to each opponent."; }
			}
			
			if ($maorule != "" && $bartok==1)
			{
				$rules .= paragraph(randword("The player with the highest score at the end of a hand may invent a $maorule, to apply to all future rounds. New ".$maorule."s exist alongside older ones.",
											 "The player with the highest score at the end of a hand may invent a $maorule, to apply to all future rounds, or repeal an existing $maorule."));
			}
		}

		if ($nullo == 1 && preg_match("/loses/",$playpara) && preg_match("/(gains|score)/",$playpara)) { $playpara .= "(If the hand was played low then each player <i>gains</i> points instead of losing them, and vice versa.)"; }
		elseif ($nullo == 1 && preg_match("/loses/",$playpara)) { $playpara .= "(If the hand was played low then each player <i>gains</i> points instead of losing them.)"; }
		elseif ($nullo == 1) { $playpara .= "(If the hand was played low then each player <i>loses</i> that many points.)"; }

		// WASHUP FOR NEXT ROUND
		$endpoint = 0;

		if ($president!="")
		{
			if ($vice != "")
			{ $playpara .= "The ".randword($president,$vice,$asshole)." starts the next round."; }
			else
			{ $playpara .= "The ".randword($president,$president,$asshole)." starts the next round."; }
		}

		if ($president!="")
		{
			if (rand(1,$rarity)==1 || ($assholescore == 0 && rand(1,2)==1)) { $playpara .= "After cards are dealt for a new round the $asshole must give the $president their highest card, and the $president gives back any card in exchange."; }
			elseif (rand(1,$rarity)==1 || $assholescore == 0) { $playpara .= "After cards are dealt for a new round the $asshole must give the $president their two highest cards, and the $president gives back any ".randword("two","three")." cards in exchange."; }
			elseif (rand(1,$rarity)==1)
			{
				$playpara .= "Before players pick up their cards for the new round, each player reveals the top card of their hand. The $president swaps their hand for the one with the highest revealed card";
				if ($vice != "") { $playpara .= " and the $vice swaps theirs for the second-highest"; }
				$playpara .= ".";
			}
			elseif (rand(1,$rarity)==1)
			{
				$playpara .= "The $asshole gathers and deals all cards for the next round.";
				if (rand(1,$rarity)==1)
				{
					$playpara .= "If any other player picks up the play pile or attempts to deal, they automatically become the $asshole for the next round.";
				}
			}
		}
		
		// HOW THE GAME ENDS

		if ($countdown==1)
		{
			$playpara .= "The first player to get rid of all their ".$currency."s is the winner.";
		}
		else
		{
			if (rand(1,$rarity)==1)
			{
				$playpara .= randword("Play as many rounds as there are players.",
										"Each successive hand is played with one fewer card, down to just one card each, after which hand the game ends.",
										"Each successive hand is played with one fewer card, down to just one card each, then one additional card back up to $handsizeword cards. After the final $handsizeword-card hand, the game ends.");
				$endpoint = 1;
			}
			
			$rules .= paragraph($playpara);
			$playpara = "";

			if ($landlord != "") { $goodhandscore += $maxplayers*.6; }

			if ($endpoint == 1)
			{
				$playpara .= "The ".randword("player with the highest score","highest-scoring player")." ".randword("wins","is the winner","wins the game")."."; 
			}
			else
			{
				$target = rand($goodhandscore*5*.7,$goodhandscore*5*1.4);
				if ($target>50)
				{ $target = ceil($target/10)*10; }

				if ($losingpoints == 1)
				{ $playpara .= "When a player's score reaches -".$target." or lower the player with the highest score wins the game."; } 
				else
				{ $playpara .= "The first player to reach ".$target." points wins the game."; } 
			}
			$playpara .= "If tied, ".randword("play an extra round","the tied player who won a round latest in the game wins").".";
		}
		$rules .= paragraph($playpara);
		$playpara = "";
	
		if ($seconddiscard == 1)
		{ $rules = preg_replace("/the (discards|discard pile)/","either discard pile",$rules); }

		$intro = randWord("<b>$gameName</b>","<b>$gameName</b> (or <b>$alsoName</b>)","<b>$gameName</b> (also <b>$alsoName</b> or <b>$thirdName</b>)");

		$adjective = qualityAdjective();
		if (sizeof($adjectives)>0) { $adjective = $adjectives[array_rand($adjectives)]; }

		$intro .= randWord(" is a $adjective ".gametypename($gametype)." game for ".numberword($minplayers)." to ".numberword($maxplayers)." players",
							" is a $adjective ".countryfromcode($countrycode)." ".gametypename($gametype)." game for ".numberword($minplayers)." to ".numberword($maxplayers)." players");
		
		if (sizeof($facts)>0) { $intro .= " where ".$facts[array_rand($facts)].". It uses $decktype. "; }
		else { $intro .= ", played with $decktype. "; }
	 
		$rules = preg_replace("/!INTRODUCTION!/",paragraph($intro,0),$rules); 
	
		return $rules;
	}
	
?>