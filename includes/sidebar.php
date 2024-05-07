<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" style="background-color:#ffffff;" href="index.php">
        <img src="../img/logo.svg" alt="Logo Seal" style="width: 90%;">
    </a>
    <!-- Divider -->

    <?php 
        $page = 0;

        $module = $pdo->prepare("SELECT 
                 m.idmodulo
                ,r.idrotina 
                ,r.nomerotina
                ,r.nomepagina
                ,r.iconeclass
                ,p.visualizar
                ,(SELECT descmodulo FROM sealmodulos WHERE idmodulo = r.idmodulo LIMIT 1) AS descmodulo
	            ,(SELECT iconeclass FROM sealmodulos WHERE idmodulo = r.idmodulo LIMIT 1) AS iconeclass
            FROM sealrotinas r
            JOIN sealmodulos m ON m.idmodulo = r.idmodulo
            LEFT JOIN sealpermissoesperfil p ON p.idpagina = r.idrotina
            LEFT JOIN sealperfil s ON s.idperfil = p.idperfil
            WHERE s.idperfil = {$_SESSION['userAuth']['idPerfil']}
            ORDER BY m.ordem
            ");
        $module->execute();
        $moduleResult = $module->fetchAll(PDO::FETCH_OBJ);

        //Variavel criada para administrar os módulos
        $modules = '';
        //Variável que se encarrega de criar as linhas e no final exibir o menu devidamente montado
        $lines = '';

        foreach ($moduleResult as $key => $valueModule) {
            //Verifica se o perfil logado pode visualizar a rotina em questão
            if ($valueModule->visualizar != 0) {
               //Verifica se a rotina esta vinculada a algum módulo
                if ($valueModule->idmodulo != 0) {
                    //Verifica se o id do módulo é diferente, para que não se repita
                    if ($modules != $valueModule->idmodulo) {
                        $modules = $valueModule->idmodulo;
                        //Consulta as Rotinas que fazem parte do módulo
                        $routines = $pdo->prepare("SELECT 
                                    r.idrotina 
                                ,r.nomerotina
                                ,r.nomepagina
                                ,r.iconeclass
                                ,p.visualizar
                                ,m.nomemodulo
                            FROM sealrotinas r
                            JOIN sealmodulos m ON m.idmodulo = r.idmodulo
                            LEFT JOIN sealpermissoesperfil p ON p.idpagina = r.idrotina
                            LEFT JOIN sealperfil s ON s.idperfil = p.idperfil
                            WHERE s.idperfil = {$_SESSION['userAuth']['idPerfil']}
                            AND m.idmodulo = $valueModule->idmodulo
                            ORDER BY r.ordem
                        ");
                        $routines->execute();
                        $routineResult = $routines->fetchAll(PDO::FETCH_OBJ);

                        foreach ($routineResult as $key => $valueRoutine) {
                            if ($page == $valueRoutine->idrotina) {
                                $active = "active";
                                $display = "display:block";																

                                break;
                            }else {
                                $active = "";
								$display = "";	
                            }

                            // pg_result_seek($routineResult, 0);
                        }

                        //Inicia a montagem visual do dos módulos e rotinas
                        $lines .= "
                            <li class='nav-item ".$active."'>
                                <a class='nav-link collapsed' href='#' data-bs-toggle='collapse' data-bs-target='#".$valueModule->idmodulo."'
                                    aria-expanded='true' aria-controls='".$valueModule->idmodulo."'>
                                    <i class='".$valueModule->iconeclass."'></i>
                                    <span>".$valueModule->descmodulo."</span>
                                </a>
                                <div id='".$valueModule->idmodulo."' class='collapse' aria-labelledby='headingTwo' data-parent='#accordionSidebar'>
                                    <div class='bg-light py-2 collapse-inner rounded'>";
                                        foreach ($routineResult as $key => $value) {
                                            $lines .= "<a class='collapse-item' href='?module=".strtolower($value->nomemodulo)."&subMenu=".strtolower($value->nomepagina)."'><i class='".$value->iconeclass."'></i> ".$value->nomerotina."</a>";
                                        }
                            $lines .= "</div>
                                </div>
                            </li>
                        ";
                        $lines .="<hr class='sidebar-divider'>";

                    }
                } 
            }
        }
        echo "<hr class='sidebar-divider'>";
        echo $lines;
    ?>

    <!-- Exemplo de módulo -->
    
    <!-- Nav Item -->
    <!--<hr class="sidebar-divider">
    <li class="nav-item">
        <a class="nav-link" href="charts.html">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>Modulo</span></a>
    </li> -->

    <!-- Botão que Diminui o sidebar -->
    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>