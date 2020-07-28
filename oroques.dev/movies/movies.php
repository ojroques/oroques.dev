<!doctype html>

<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=1024">

        <title>Movies</title>
        <meta name="description" content="List of movies seen">
        <meta name="author" content="Olivier Roques">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
        <!-- DataTables CSS -->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">
        <!-- CSS -->
        <link rel="stylesheet" href="style.css">
    </head>

    <body>
        <?php
            $full = false;
            if (isset($_POST['full'])) {
                $full = ($_POST['full'] === 'true');
            }
        ?>

        <div class="container-fluid my-3">
            <div class="row align-items-center mb-3">
                <div class="col-2 offset-1 text-left">
                    <a class="btn btn-link" href="https://oroques.dev" role="button">Main website</a>
                </div>
                <div class="col-4 offset-1 text-center">
                    <h1>Movies</h1>
                </div>
                <div class="col-2 offset-1 text-right">
                    <?php if ($full) {
                        echo "<form action='movies.php' method='post'>\n";
                        echo "<button name='full' value='false' class='btn btn-primary' type='submit'>Reduced list</button>\n";
                    } else {
                        echo "<form action='movies_full.php' method='post'>\n";
                        echo "<button name='full' value='true' class='btn btn-primary' type='submit'>Full list</button>\n";
                    }
                    echo "</form>\n"; ?>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-10">
                    <p>
                    This page contains the list of all the films I have seen since July 2012. The data come from a SQL database that has been regularly updated since then. Some columns have deliberatly been removed.
                    <?php if ($full) {
                        echo "</br>Entries without <q>seen date</q> are movies I have watched before the creation of this list.";
                    } ?>
                    </p>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-10">
                    <table id="movies" class="display">
                        <thead>
                            <tr>
                                <th>Original title</th>
                                <th>French title</th>
                                <th>Film director</th>
                                <th>Country</th>
                                <th>Release date</th>
                                <th>Seen date</th>
                                <?php if ($full) {echo "<th>Rating</th>";} ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                include 'table.php';
                                buildTable($full);
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
        <!-- Bootstrap JS -->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
        <!-- DataTable JS -->
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment.min.js" integrity="sha512-rmZcZsyhe0/MAjquhTgiUcb4d9knaFc7b5xAfju483gbEXTkeJRUMIPk6s3ySZMYUHEcjKbjLjyddGWMrNEvZg==" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/plug-ins/1.10.21/sorting/datetime-moment.js"></script>
        <script>
            $(document).ready(function() {
                $.fn.dataTable.moment('MMM YYYY', 'en');
                $('#movies').DataTable({
                    paging: false,
                    scrollY: "72vh",
                    columns: [
                        {className: "dt-head-center"},
                        {className: "dt-head-center"},
                        {className: "dt-head-center"},
                        {className: "dt-head-center dt-nowrap"},
                        {className: "dt-head-center dt-body-right dt-nowrap"},
                        {className: "dt-head-center dt-body-right dt-nowrap"},
                        <?php if ($full) {
                            echo '{className: "dt-center dt-nowrap"},';
                        } ?>
                    ],
                    order: [[5, "desc"], [1, "asc"]],
                    language: {
                        info: "_TOTAL_ out of _MAX_ movies.",
                        search: "Search:",
                        infoFiltered: "",
                        infoEmpty: "No results.",
                    },
                });
            });
        </script>
    </body>
</html>
