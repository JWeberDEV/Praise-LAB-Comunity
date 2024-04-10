<?php
  $parent = "i'm father";
  require_once(__DIR__ . "/php/_protect.php");
  require_once(__DIR__ . "/php/_db.php");
$routine = "";
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <?php require_once(__DIR__ . "/includes/head.php"); ?>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

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
            <div id="content">

                <!-- Topbar -->
                <?php require_once(__DIR__ . "/includes/navbar.php"); ?>
                <!-- End of Topbar -->

                <?php
                    $module = isset($_GET['module']) ? $_GET['module'] : 'unknow';
                    $subMenu = isset($_GET['routine']) ? $_GET['routine'] : 'unknow';
                    switch ($module) {
                        case "admin":
                            require_once(__DIR__ . "/pages/admin/route.php");
                            break;

                        case 'dashboard':
                            require_once(__DIR__ . "/pages/dashboard/route.php");
                            break;
                            
                        case 'operacional':
                            require_once(__DIR__ . "/pages/operacional/route.php");
                            break;

                        default:
                            $module = "home";
                            require_once(__DIR__ . "/pages/home/route.php");
                    }
                ?>
            <input type="hidden" name="module" value="<?php echo $module;?>">
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer" style="background-color:#eaecf2;">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Seal <script> document.write(new Date().getFullYear()) </script></span>
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

<?php require_once(__DIR__ . "/includes/js_includes.php"); ?>

<script>
    $(document).ready(function() {
        $('.blocked-btn').hide();
    });
</script>