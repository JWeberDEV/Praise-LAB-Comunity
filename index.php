<?php
  $parent = "i'm father";
  require_once(__DIR__ . "/php/_protect.php");
  require_once(__DIR__ . "/php/_db.php");
$routine = "";
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <?php require_once(__DIR__ . "/includes/head.php"); ?>
</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php require_once(__DIR__ . "/includes/sidebar.php"); ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content" class="bg-content">

                <!-- Topbar -->
                <?php require_once(__DIR__ . "/includes/navbar.php"); ?>
                <!-- End of Topbar -->

                <?php
                    require_once(__DIR__ . "/php/routes.php");
                    $query_route =isset($_GET['route']) ? $_GET['route'] : 'unknown';
                    if (isset($routes[$query_route]) != null) {
                        require_once(__DIR__ ."/pages/$routes[$query_route]");
                    } else {
                        require_once(__DIR__ ."/pages/default_content.php");
                    }
                ?>
            <input type="hidden" name="module" value="<?php echo $module;?>">
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer" style="background-color:#202428;">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Praise Lab <script> document.write(new Date().getFullYear()) </script></span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

</body>

</html>