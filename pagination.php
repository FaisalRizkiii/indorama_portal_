<?php
// Pagination Controls
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) . '?';
foreach ($_GET as $key => $value) {
    if ($key != 'page') {
        $uri .= "$key=$value&";
    }
}

$max_links = 5;
$start_link = max(1, $current_page - floor($max_links / 2));
$end_link = min($total_pages, $start_link + $max_links - 1);
$start_link = max(1, $end_link - $max_links + 1); // Adjust start if end is modified

echo '<nav><ul class="pagination">';

if ($current_page > 1) {
    echo '<li class="page-item"><a class="page-link" href="' . $uri . 'page=' . ($current_page - 1) . '">Previous</a></li>';
}

for ($i = $start_link; $i <= $end_link; $i++) {
    $active = ($i == $current_page) ? ' active' : '';
    echo '<li class="page-item' . $active . '"><a class="page-link" href="' . $uri . 'page=' . $i . '">' . $i . '</a></li>';
}

if ($current_page < $total_pages) {
    echo '<li class="page-item"><a class="page-link" href="' . $uri . 'page=' . ($current_page + 1) . '">Next</a></li>';
}
echo '</ul></nav>';
?>
