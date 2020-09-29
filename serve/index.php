<?php

require_once '../site.php';
$db->connect();
require_auth();

site_header();
printf('<div class="table"><table id="torrents"><tr><th>Torrent Name</th><th class="center">Download</th><th class="center">Added on</th><th class="center">Uploader</th><th class="center">Size</th></tr>');
$res = $db->query_params("SELECT torrent_id, anonymous, name, username, users.user_id, submitted, total_size FROM torrents JOIN users ON users.user_id = torrents.user_id ORDER BY submitted DESC;");
if ($res) {
    while ($row = $res->fetch()) {

        $submitted = $db->get_datetime($row['submitted']);
        printf('<tr><td><a href="torrent.php?id=%s">%s</a></td><td class="center"><a href="download.php?id=%s">DL</a></td><td class="center" title="%s">%s</td>', $row['torrent_id'], html_escape($row['name']), $row['torrent_id'], html_escape($submitted->format('c')), html_escape($submitted->format('d/m/Y g:i a')));

        if ($row['anonymous']) {
            printf('<td class="center"><i>anonymous</i></td>');
        } else {
            printf('<td class="center"><a href="%s">%s</td></a>', html_escape(site_url() . "/user.php?id=" . $row['user_id']), html_escape($row['username']));
        }
        printf('<td class="center">%s</td>', format_size($row['total_size']));
        printf('</tr>');
    }
}
print('</tbody></table></div>');
site_footer();

