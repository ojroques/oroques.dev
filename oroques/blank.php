<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="author" content="Olivier Roques">
        <meta name="description" content="A blank page.">
        <title>blank</title>
        <?php
            $color = "ffffff";
            if (isset($_GET['c'])) {
                if (ctype_xdigit($_GET['c']) && strlen($_GET['c']) == 6) {
                    $color = strtolower($_GET['c']);
                }
            }
            printf("<style>body {background-color: #%s;}</style>", $color);
        ?>
    </head>
    <body></body>
</html>
