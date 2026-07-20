<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
    <div class="container-fluid">
        <!-- ===== LOGO ===== -->
        <a class="navbar-brand d-flex align-items-center" href="index.php?View=home">
            <div class="logo-container">
                <img src="./Public/img/logo.PNG" alt="Logo" class="logo-img">
                <div class="logo-text">
                    <span class="brand-title">Deportivo Los Castores</span>
                    <span class="brand-subtitle">Academia de Fútbol</span>
                </div>
            </div>
        </a>

        <!-- ===== BOTONES DE NAVEGACIÓN RÁPIDA (SOLO EN ESCRITORIO) ===== -->
        <div class="nav-buttons d-none d-lg-flex">
            <a href="index.php?View=Gestion_usuarios" class="btn-nav">
                <i class="bi bi-people-fill"></i>
                <span>Usuarios</span>
            </a>
            <a href="index.php?View=ReportesAdministrador" class="btn-nav">
                <i class="bi bi-journal-bookmark-fill"></i>
                <span>Reportes</span>
            </a>
            <a href="index.php?View=Noticias" class="btn-nav">
                <i class="bi bi-newspaper"></i>
                <span>Noticias</span>
            </a>
        </div>

        <!-- ===== TOGGLER MÓVIL ===== -->
        <button class="navbar-toggler btn-toggler-custom" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAdmin" aria-controls="navbarNavAdmin" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- ===== MENÚ COLLAPSE ===== -->
        <div class="collapse navbar-collapse" id="navbarNavAdmin">
            
            <!-- ===== BOTONES DE NAVEGACIÓN (MÓVIL) ===== -->
            <div class="d-lg-none nav-buttons-mobile">
                <a href="index.php?View=Gestion_usuarios" class="btn-nav-mobile">
                    <i class="bi bi-people-fill"></i>
                    <span>Usuarios</span>
                </a>
                <a href="index.php?View=ReportesAdministrador" class="btn-nav-mobile">
                    <i class="bi bi-journal-bookmark-fill"></i>
                    <span>Reportes</span>
                </a>
                <a href="index.php?View=Noticias" class="btn-nav-mobile">
                    <i class="bi bi-newspaper"></i>
                    <span>Noticias</span>
                </a>
            </div>

            <!-- ===== PERFIL Y SESIÓN ===== -->
            <div class="d-flex align-items-center dropdown ms-lg-auto">
                <a class="nav-link dropdown-toggle profile-trigger d-flex align-items-center" 
                   href="#" id="navbarDropdownProfile" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="profile-avatar">
                        <?php 
                        $iniciales = strtoupper(substr($_SESSION['usuario']['nombre'] ?? 'U', 0, 1) . substr($_SESSION['usuario']['apellido'] ?? 'U', 0, 1));
                        echo $iniciales;
                        ?>
                    </div>
                    <div class="profile-info d-none d-sm-block">
                        <span class="profile-name"><?php echo $_SESSION['usuario']['nombre'] . ' ' . $_SESSION['usuario']['apellido']; ?></span>
                        <span class="profile-role"><?php echo $_SESSION['usuario']['id_rol']; ?></span>
                    </div>
                    <i class="bi bi-chevron-down dropdown-icon"></i>
                </a>
                
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-custom shadow" aria-labelledby="navbarDropdownProfile">
                    <li>
                        <a class="dropdown-item" href="index.php?View=MiPerfil">
                            <i class="bi bi-person-gear me-2"></i> Mi Perfil
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item text-danger" href="index.php?View=Logout">
                            <i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión
                        </a>
                    </li>
                </ul>
            </div>

        </div>
    </div>
</nav>