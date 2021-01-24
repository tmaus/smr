<?php declare(strict_types=1);

$template->assign('PageTopic', 'Death Rankings');

Menu::rankings(0, 3);

// what rank are we?
$ourRank = $player->getDeathsRank();
$template->assign('OurRank', $ourRank);

$totalPlayers = $player->getGame()->getTotalPlayers();

$template->assign('Rankings', Rankings::playerRanks('deaths'));

list($minRank, $maxRank) = Rankings::calculateMinMaxRanks($ourRank, $totalPlayers);

$template->assign('FilterRankingsHREF', SmrSession::getNewHREF(create_container('skeleton.php', 'rankings_player_death.php')));

$template->assign('FilteredRankings', Rankings::playerRanks('deaths', $minRank, $maxRank));
