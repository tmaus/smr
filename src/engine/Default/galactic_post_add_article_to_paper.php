<?php declare(strict_types=1);

$session = Smr\Session::getInstance();
$player = $session->getPlayer();

//limit 4 per paper...make sure we arent over that
$db->query('SELECT * FROM galactic_post_paper_content WHERE game_id = ' . $db->escapeNumber($player->getGameID()) . ' AND paper_id = ' . $db->escapeNumber($var['paper_id']));
if ($db->getNumRows() >= 8) {
	create_error('You can only have 8 articles per paper.');
}
$db->query('INSERT INTO galactic_post_paper_content (game_id, paper_id, article_id) VALUES (' . $db->escapeNumber($player->getGameID()) . ', ' . $db->escapeNumber($var['paper_id']) . ', ' . $db->escapeNumber($var['id']) . ')');
//we now have that article in the paper
$container = Page::create('skeleton.php', 'galactic_post_view_article.php');
$container->go();
