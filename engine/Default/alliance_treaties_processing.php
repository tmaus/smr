<?php

//get the alliances
if (!isset($var['alliance_id'])) {
	SmrSession::updateVar('alliance_id',$player->getAllianceID());
}
$alliance_id_1 = $var['alliance_id'];
if ($alliance_id_1 == 0) create_error('You are not in an alliance!');
if (isset($var['accept'])) {
	if ($var['accept']) {
		$db->query('UPDATE alliance_treaties SET official = \'TRUE\' WHERE alliance_id_1 = ' . $db->escapeNumber($var['alliance_id_1']) . ' AND alliance_id_2 = ' . $db->escapeNumber($alliance_id_1) . ' AND game_id = ' . $db->escapeNumber($player->getGameID()));
		if ($var['aa']) {
			//make an AA entry to the alliance, use treaty_created column
			// get last id
			$db->query('SELECT MAX(role_id)
						FROM alliance_has_roles
						WHERE game_id = ' . $db->escapeNumber($player->getGameID()) . '
							AND alliance_id = ' . $db->escapeNumber($alliance_id_1));
			if ($db->nextRecord()) {
				$role_id = $db->getInt('MAX(role_id)') + 1;
			}
			$allianceName = $var['alliance_name'];
			$db->query('INSERT INTO alliance_has_roles
				(alliance_id, game_id, role_id, role, treaty_created)
				VALUES (' . $db->escapeNumber($alliance_id_1) . ', ' . $db->escapeNumber($player->getGameID()) . ', ' . $db->escapeNumber($role_id) . ', ' . $db->escapeString($allianceName) . ',1)');
			$db->query('SELECT MAX(role_id)
						FROM alliance_has_roles
						WHERE game_id = ' . $db->escapeNumber($player->getGameID()) . '
							AND alliance_id = ' . $db->escapeNumber($var['alliance_id_1']));
			if ($db->nextRecord()) {
				$role_id = $db->getInt('MAX(role_id)') + 1;
			}
			$allianceName = $player->getAllianceName();
			$db->query('INSERT INTO alliance_has_roles
				(alliance_id, game_id, role_id, role, treaty_created)
				VALUES (' . $db->escapeNumber($var['alliance_id_1']) . ', ' . $db->escapeNumber($player->getGameID()) . ', ' . $db->escapeNumber($role_id) . ', ' . $db->escapeString($allianceName) . ',1)');
		}
	}
	else {
		$db->query('DELETE FROM alliance_treaties WHERE alliance_id_1 = ' . $db->escapeNumber($var['alliance_id_1']) . ' AND alliance_id_2 = ' . $db->escapeNumber($alliance_id_1) . ' AND game_id = ' . $db->escapeNumber($player->getGameID()));
	}
	$container = create_container('skeleton.php','alliance_treaties.php');
	$container['alliance_id'] = $alliance_id_1;
	forward($container);
}
$alliance1 =& SmrAlliance::getAlliance($alliance_id_1, $player->getGameID());
if (isset($_REQUEST['proposedAlliance'])) {
	$alliance_id_2 = $_REQUEST['proposedAlliance'];
	$db->query('SELECT alliance_id_1, alliance_id_2, game_id FROM alliance_treaties WHERE (alliance_id_1 = ' . $db->escapeNumber($alliance_id_1) . ' OR alliance_id_1 = '.$alliance_id_2.') AND (alliance_id_2 = ' . $db->escapeNumber($alliance_id_1) . ' OR alliance_id_2 = ' . $db->escapeNumber($alliance_id_2) . ') AND game_id = ' . $db->escapeNumber($player->getGameID()));
	if ($db->nextRecord()) {
		$container=create_container('skeleton.php', 'alliance_treaties.php');
		$container['alliance_id'] = $alliance_id_1;
		$container['message'] = '<span class="red bold">ERROR:</span> There is already an outstanding treaty with that alliance.';
		forward($container);
	}
	//get the terms
	$traderAssist = isset($_REQUEST['assistTrader']);
	$raidAssist = isset($_REQUEST['assistRaids']);
	$traderDefend = isset($_REQUEST['defendTrader']);
	$traderNAP = $traderDefend || $traderAssist || isset($_REQUEST['napTrader']);
	$planetLand = isset($_REQUEST['planetLand']);
	$planetNAP = $planetLand ||isset($_REQUEST['napPlanets']);
	$forcesNAP = isset($_REQUEST['napForces']);
	$aaAccess = isset($_REQUEST['aaAccess']);
	$mbWrite = isset($_REQUEST['mbWrite']);
	$mbRead = $mbWrite || isset($_REQUEST['mbRead']);
	$modRead = isset($_REQUEST['modRead']);
	//get confirmation
	$template->assign('PageTopic',$alliance1->getAllianceName() . ' (' . $alliance1->getAllianceID() . ')');
	require_once(get_file_loc('menu.inc'));
	create_alliance_menu($alliance1->getAllianceID(),$alliance1->getLeaderID());
	$PHP_OUTPUT.=('<br /><br /');
	$PHP_OUTPUT.=('<div align="center">Are you sure you want to offer a treaty to <span class="yellow">');
	$db->query('SELECT leader_id, alliance_name, alliance_id FROM alliance WHERE game_id=' . $db->escapeNumber($player->getGameID()) . ' AND alliance_id=' . $db->escapeNumber($alliance_id_2) . ' LIMIT 1');
	$db->nextRecord();
	$PHP_OUTPUT.=(stripslashes($db->getField('alliance_name')));
	$PHP_OUTPUT.=('</span> with the following conditions:<br /><ul>');
	if ($traderAssist) $PHP_OUTPUT.=('<li>Assist - Trader Attacks</li>');
	if ($traderDefend) $PHP_OUTPUT.=('<li>Defend - Trader Attacks</li>');
	if ($traderNAP) $PHP_OUTPUT.=('<li>Non Aggression - Traders</li>');
	if ($raidAssist) $PHP_OUTPUT.=('<li>Assist - Planet & Port Attacks</li>');
	if ($planetNAP) $PHP_OUTPUT.=('<li>Non Aggression - Planets</li>');
	if ($forcesNAP) $PHP_OUTPUT.=('<li>Non Aggression - Forces</li>');
	if ($aaAccess) $PHP_OUTPUT.=('<li>Alliance Account Access</li>');
	if ($mbRead) $PHP_OUTPUT.=('<li>Message Board Read Rights</li>');
	if ($mbWrite) $PHP_OUTPUT.=('<li>Message Board Write Rights</li>');
	if ($modRead) $PHP_OUTPUT.=('<li>Message of the Day Read Rights</li>');
	if ($planetLand) $PHP_OUTPUT.=('<li>Planet Landing Rights</li>');
	$PHP_OUTPUT.=('</ul>');

	//give them options
	$container=create_container('alliance_treaties_processing.php');
	$container['alliance_id'] = $alliance_id_1;
	$container['proposedAlliance'] = $alliance_id_2;
	$container['traderAssist'] = $traderAssist;
	$container['raidAssist'] = $raidAssist;
	$container['traderDefend'] = $traderDefend;
	$container['traderNAP'] = $traderNAP;
	$container['planetNAP'] = $planetNAP;
	$container['forcesNAP'] = $forcesNAP;
	$container['aaAccess'] = $aaAccess;
	$container['mbRead'] = $mbRead;
	$container['mbWrite'] = $mbWrite;
	$container['modRead'] = $modRead;
	$container['planetLand'] = $planetLand;
	$PHP_OUTPUT.=create_button($container,'Yes');
	$PHP_OUTPUT.=('&nbsp;');
	$container=create_container('skeleton.php', 'alliance_treaties.php');
	$container['alliance_id'] = $alliance_id_1;
	$PHP_OUTPUT.=create_button($container,'No');
	$PHP_OUTPUT.=('</div>');

} else {
	$alliance_id_2 = $var['proposedAlliance'];
	$db->query('INSERT INTO alliance_treaties (alliance_id_1,alliance_id_2,game_id,trader_assist,trader_defend,trader_nap,raid_assist,planet_land,planet_nap,forces_nap,aa_access,mb_read,mb_write,mod_read,official)
				VALUES (' . $db->escapeNumber($alliance_id_1) . ', ' . $db->escapeNumber($alliance_id_2) . ', ' . $db->escapeNumber($player->getGameID()) . ', ' . $db->escapeBoolean($var['traderAssist']) . ', ' .
				$db->escapeBoolean($var['traderDefend']) . ', ' . $db->escapeNumber($var['traderNAP']) . ', ' . $db->escapeBoolean($var['raidAssist']) . ', ' . $db->escapeBoolean($var['planetLand']) . ', ' . $db->escapeBoolean($var['planetNAP']) . ', ' .
				$db->escapeBoolean($var['forcesNAP']) . ', ' . $db->escapeBoolean($var['aaAccess']) . ', ' . $db->escapeBoolean($var['mbRead']) . ', ' . $db->escapeBoolean($var['mbWrite']) . ', ' . $db->escapeBoolean($var['modRead']) . ', \'FALSE\')');
	//send a message to the leader letting them know the offer is waiting.
	$db->query('SELECT leader_id FROM alliance WHERE game_id=' . $db->escapeNumber($player->getGameID()) . ' AND alliance_id=' . $db->escapeNumber($alliance_id_2) . ' LIMIT 1');
	$db->nextRecord();
	$leader_2 = $db->getField('leader_id');
	$message = 'An ambassador from <span class="yellow">' . $alliance1->getAllianceName() . '</span> has arrived.';

	SmrPlayer::sendMessageFromAllianceAmbassador($player->getGameID(), $leader_2, $message, MESSAGE_EXPIRES);
	$container=create_container('skeleton.php', 'alliance_treaties.php');
	$container['alliance_id'] = $alliance_id_1;
	$container['message'] = 'The treaty offer has been sent.';
	forward($container);
}