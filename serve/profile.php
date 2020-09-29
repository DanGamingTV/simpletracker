<?php

require_once '../site.php';
require_once '../bencoding.php';
$db->connect();
require_auth();

$row = false;
if (array_key_exists('user_id', $_SESSION['user'])) {
    $id = $_SESSION['user']['user_id'];
    $res = $db->query_params("SELECT torrent_id, anonymous, name, username, users.user_id, submitted, total_size FROM torrents JOIN users ON users.user_id = torrents.user_id WHERE torrents.user_id = :userid ORDER BY submitted DESC;", array('userid' => $id));
}
site_header();

$user_shown = false;
if ($res) {
    $has_id = false;
    while ($row = $res->fetch()) {
        $has_id = true;
        if ($user_shown == false) {
            printf('<div class="info"><h1>Your profile</h1></div>');
            printf('<div class="table"><table id="torrents"><tr><th>Torrent Name</th><th class="center">Download</th><th class="center">Added on</th><th class="center">Size</th></tr>');
        }
        $submitted = $db->get_datetime($row['submitted']);
        printf('<tr><td><a href="torrent.php?id=%s">%s</a></td><td class="center"><a href="download.php?id=%s">DL</a></td><td class="center" title="%s">%s</td>', $row['torrent_id'], html_escape($row['name']), $row['torrent_id'], html_escape($submitted->format('c')), html_escape($submitted->format('d/m/Y g:i a')));


        printf('<td class="center">%s</td>', format_size($row['total_size']));
        printf('</tr>');
        $user_shown = true;
    }
    if ($has_id == false) {
        printf('<section class="info"><div class="bad notification">User not found</div></section>');
    }
}

print('</tbody></table></div>');
site_footer();

