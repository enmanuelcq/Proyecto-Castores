

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
            <a href="index.php?View=Noticias" class="btn-nav-mobile">
                <i class="bi bi-newspaper"></i>
                <span>Noticias</span>
            </a>
        </div>

        <!-- ===== PERFIL Y SESIÓN ===== -->
        <div class="d-flex align-items-center ms-lg-auto">
            <div class="nav-buttons d-none d-lg-flex">
                <a href="index.php?View=Login" class="btn-nav">Iniciar Sesión</a>
            </div>
        </div>

    </div>
</div>
</nav>