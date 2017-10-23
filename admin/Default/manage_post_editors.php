<?php

$template->assign('PageTopic', 'Manage Galactic Post Editors');

// Get the list of active games ordered by reverse start date
$activeGames = array();
$db->query('SELECT game_id, game_name FROM game WHERE start_date < ' . $db->escapeNumber(TIME) . ' AND end_date > ' . $db->escapeNumber(TIME) . ' ORDER BY start_date DESC');
while ($db->nextRecord()) {
	$activeGames[] = array('game_name' => $db->getField('game_name'),
	                       'game_id' => $db->getInt('game_id'));
}
$template->assign('ActiveGames', $activeGames);

if ($activeGames) {
	// Set the selected game (or the first in the list if not selected yet)
	if (isset($_POST['game_id'])) {
		SmrSession::updateVar('selected_game_id', $_POST['game_id']);
		SmrSession::updateVar('processing_msg', null);
	} else if (!isset($var['selected_game_id'])) {
		SmrSession::updateVar('selected_game_id', $activeGames[0]['game_id']);
	}
	$template->assign('SelectedGame', $var['selected_game_id']);

	// Get the list of current editors for the selected game
	$currentEditors = array();
	$db->query('SELECT account_id FROM galactic_post_writer WHERE game_id=' . $db->escapeNumber($var['selected_game_id']) . ' AND position=' . $db->escapeString('editor'));
	while ($db->nextRecord()) {
		$editor = SmrPlayer::getPlayer($db->getInt('account_id'),
		                               $var['selected_game_id']);
		$currentEditors[] = $editor->getDisplayName();
	}
	$template->assign('CurrentEditors', $currentEditors);
}

// If we have just forwarded from the processing file, pass its message.
$template->assign('ProcessingMsg', $var['processing_msg']);

// Create the link to the processing file
// Pass entire $var so the processing file knows the selected game
$linkContainer = create_container('manage_post_editors_processing.php', '', $var);
$template->assign('PostEditorHREF', SmrSession::getNewHREF($linkContainer));

?>
