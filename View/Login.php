<div class="login-background">
    <div class="login-card text-center">
        
        <img src="Public/img/logo.png" alt="Logo Los Castores" class="brand-logo mb-2">
        <h5 class="fw-bold mb-0" style="letter-spacing: 0.5px;">Deportivo Los Castores</h5>
        <p class="text-muted small mb-4">Academia de Fútbol</p>

        <h2 class="text-start">Bienvenido</h2>
        <p class="text-start subtitle-welcome">Ingrese sus credenciales para acceder</p>

        <form action="./Controller/Login_controller.php" method="POST" class="FormularioAjax" id="loginForm">
            
            <div class="mb-3 text-start">
                <label for="username" class="form-label">Nombre de usuario</label>
                <input type="text" name="username" id="username" pattern="[a-zA-Z0-9.,]{4,30}" maxlength="30" class="form-control form-control-custom" placeholder="Ingrese su nombre de usuario" required>
            </div>

            <div class="mb-3 text-start">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <label for="password" class="form-label mb-0">Contraseña</label>
                </div>
                <div class="password-wrapper">
                    <input type="password" name="password" id="password" pattern="[a-zA-Z0-9$@#.]{7,99}" maxlength="100" class="form-control form-control-custom w-100" placeholder="••••••••" required>
                    <i class="bi bi-eye password-toggle-icon" id="togglePassword"></i>
                </div>
            </div>

            <div class="mb-4 text-start d-flex align-items-center">
                <input type="checkbox" name="remember" id="remember" class="form-check-input me-2" style="cursor: pointer;">
                <label class="form-check-label small" Jack for="remember" style="cursor: pointer; color: #333;">Recordar mi sesión</label>
            </div>

            <div class="d-grid gap-2 justify-content-center">
                <button type="submit" class="btn-submit-login d-flex align-items-center gap-2">
                    Iniciar sesión <i class="bi bi-box-arrow-in-right"></i>
                </button>
            </div>
        </form><br>

        <div class="form-rest"></div>

        <div class="register-footer">
            ¿Eres Representante? <a href="#">Activa tu cuenta</a>
        </div>

    </div>
</div>

<script src="./Public/Js/Login.js"></script>