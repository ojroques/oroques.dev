<!doctype html>

<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <title>Movies</title>
        <meta name="description" content="List of movies seen">
        <meta name="author" content="Olivier Roques">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <!-- DataTables CSS -->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
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
                        echo "</br>Movies without \"seen date\" are some movies I have watched before the creation of this list.";
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

        <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <!-- Bootstrap JS -->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
        <!-- DataTable JS -->
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js" integrity="sha256-4iQZ6BVL4qNKlQ27TExEhBN1HFPvAvAMbFavKKosSWQ=" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/plug-ins/1.10.19/sorting/datetime-moment.js"></script>
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
