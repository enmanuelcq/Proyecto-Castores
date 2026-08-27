# Deportivo Los Castores - Sistema de Gestión Academia de Fútbol

Sistema web integral para la administración y gestión de la academia de fútbol "Deportivo Los Castores". Plataforma orientada al seguimiento deportivo, administrativo y operativo del club.

---

## Descripción General

El sistema permite gestionar de forma centralizada el registro de atletas, control de asistencias, registro de entrenamientos, convocatoria y seguimiento de partidos, evaluaciones deportivas (pruebas), cobro de cuotas, generación de noticias y reportes administrativos. Está diseñado para ser utilizado por los distintos roles del club: administradores, secretaría, entrenadores y representantes.

---

## Arquitectura y Stack Tecnológico

| Capa | Tecnología |
|------|-----------|
| Lenguaje | PHP 7/8 (sin framework) |
| Base de datos | MySQL via PDO (patrón Singleton) |
| Frontend CSS | Bootstrap 5.3.2 + Bootstrap Icons 1.11.1 |
| Gráficas | Chart.js 4.4.0 |
| Routing | Enrutamiento custom por parámetro GET (`?View=nombre`) |
| Autoloading | `spl_autoload_register` personalizado + Composer classmap |
| Seguridad | Tokens CSRF, headers de seguridad HTTP, sanitización de inputs |
| Servidor local | XAMPP (Apache + MySQL) |

### Estructura del Proyecto

```
Castores3/
├── BD/                    # Dump de la base de datos (castores.sql)
├── Config/                # Configuración del sistema
│   ├── Database.php       # Conexión PDO Singleton
│   ├── Session_start.php  # Gestión de sesiones + token CSRF
│   ├── main.php           # Funciones helper globales
│   ├── autoloader.php     # Carga automática de clases
│   └── bootstrap.php      # Inicialización + headers de seguridad
├── Controller/            # Controladores (40 archivos)
├── Model/                 # Modelos y lógica de negocio
│   ├── Entities/          # Entidades (clases de dominio)
│   ├── Repository/        # Repositorios de acceso a datos (29 archivos)
│   └── *.php              # Modelos por dominio (25 archivos)
├── View/                  # Vistas (16 archivos PHP)
├── Public/
│   ├── css/               # Hojas de estilo (1 global + 14 por vista)
│   ├── Js/                # Scripts JavaScript (12 archivos)
│   ├── img/               # Imágenes y logos
│   ├── Modals/            # Modales reutilizables
│   ├── Head.php           # Encabezado HTML + CDN
│   ├── Footer.php         # Pie de página
│   ├── filtro.php         # Control de acceso por rol
│   └── Navbar*.php        # Barras de navegación por rol
└── index.php              # Router principal
```

---

## Roles del Sistema

| Rol | ID | Funciones principales |
|-----|----|-----------------------|
| **Administrador** | 1 | Gestión completa del sistema: usuarios, atletas, pagos, configuración, reportes, tasa BCV |
| **Secretaría** | 2 | Registro de atletas, cobro de cuotas, atención al representante |
| **Entrenador** | 3 | Registro de entrenamientos, pruebas deportivas, convocatoria de partidos, calificaciones |
| **Representante** | 4 | Consulta del perfil del atleta, pagos, participación en partidos, rendimiento |

---

## Módulos y Funcionalidades

### Gestión de Atletas
- Registro completo con datos personales, contacto, documentación (cédula, partida de nacimiento)
- Perfil con foto, categoría, estado (activo/inactivo)
- Asignación a categorías (Sub-10 a Sub-18)
- Cambio de estado y gestión de uniformes
- Búsqueda y paginación avanzada

### Pruebas Deportivas (9 tipos)
Evaluación del rendimiento atlético con 9 tipos de prueba:

| Prueba | Mide |
|--------|------|
| **Balón Aéreo** | Toques totales, pecho, rodilla, pie |
| **Conducción** | Tiempo en recorrido con obstáculos (derecha, izquierda, ambas piernas) |
| **Definición** | Precisión de tiro (centro, derecha, izquierda, bajo/alto, empeine) |
| **Fuerza** | Flexiones, abdominales y sentadillas por minuto |
| **Pases** | Total, derecha, izquierda, primera intención |
| **Recepción de Balón** | Derecha, izquierda, orientado, con giro |
| **Resistencia** | Tiempo total y pausa |
| **Salto** | Altura inicial, 3 saltos, promedio |
| **Velocidad** | 3 intentos y promedio |

Cada prueba genera datos para dashboards de rendimiento con:
- Gráfico radar comparativo (atleta vs. promedio de categoría)
- Barras de progreso por aspecto evaluado
- Mini gráficas de evolución temporal por tipo de prueba
- Resumen de tendencia (mejorando / estable / bajando)

### Partidos y Convocatorias
- Registro de partidos con tipo (Amistoso, Torneo, Liga, Copa), categoría, rival y campo
- Sistema de convocatoria de jugadores por partido
- Carga de resultados con goles, tarjetas y duración
- Historial de partidos por atleta con filtros por categoría, tipo y resultado
- Minutos jugados por atleta en cada partido

### Entrenamientos
- Registro de sesiones de entrenamiento por categoría
- Control de asistencia de atletas
- Seguimiento de incidencias

### Gestión de Cuotas y Pagos
- Configuración de montos de cuotas mensuales
- Registro de pagos con múltiples métodos (efectivo, transferencia, Pago Móvil, divisas)
- Integración con tasa del BCV para conversiones
- Control de solvencia por atleta
- Carga manual y automatizada de cuotas

### Noticias y Comunicados
- Creación, edición y eliminación de noticias
- Asociación a partidos y categorías
- Galería de imágenes por noticia

### Dashboard de Rendimiento (Atleta)
- Perfil del atleta con datos personales y estadísticas
- Tarjetas KPI: partidos jugados, goles, tarjetas, minutos, calificación promedio
- Gráfico radar de habilidades
- Gráfico doughnut de distribución de resultados
- Barras de progreso por aspecto (3x3)
- Mini gráficas de evolución por tipo de prueba
- Pruebas recientes y partidos próximos

### Reportes y Consultas
- Reportes administrativos con filtros por fecha y categoría
- Dashboard del representante con resumen de pagos y participación

### Gestión de Usuarios
- Registro y edición de usuarios con roles
- Activación / desactivación de cuentas
- Cambio de contraseña
- Validación de cédula y usuario duplicado
- Bloqueo de inicio de sesión para usuarios inactivos

---

## Seguridad

- **Tokens CSRF** en todos los formularios POST (`bin2hex(random_bytes(32))`)
- **Headers HTTP de seguridad**: X-Content-Type-Options, X-Frame-Options, X-XSS-Protection, Referrer-Policy
- **Sanitización de inputs** mediante `limpiar_cadena()` en todos los datos recibidos
- **Preparación de consultas SQL** (PDO prepared statements) en todos los repositorios
- **Control de acceso por rol** en `filtro.php` — usuarios no autenticados solo pueden ver Home, Noticias y Login
- **Bloqueo de sesión** para usuarios desactivados

---

## Base de Datos

Base de datos `castores` con 43+ tablas, incluyendo:

| Tabla | Descripción |
|-------|------------|
| `atleta` | Datos personales de atletas |
| `categoria` | Categorías de edad (Sub-10 a Sub-18) |
| `usuario` | Usuarios del sistema con roles |
| `representante` | Representantes legales de atletas |
| `convocado` | Atletas convocados por partido |
| `partido` | Registro de partidos |
| `equipo_rival` | Equipos rivales |
| `gol` | Goles marcados por partido/atleta |
| `partido_tarjeta` | Tarjetas amarillas/rojas |
| `entrenamiento` | Sesiones de entrenamiento |
| `prueba_*` (9 tablas) | Resultados de cada tipo de prueba deportiva |
| `cuota_mensual` | Configuración de cuotas |
| `pago` | Registro de pagos |
| `noticia` | Noticias y comunicados |
| `campo` | Campos de juego |
| `uniforme` | Uniformes del club |
| `salud` | Información de salud de atletas |

---

## Configuración e Instalación

### Requisitos
- XAMPP (Apache + MySQL + PHP 7.4+)
- Navegador web actualizado

### Pasos
1. Clonar o copiar el proyecto en `C:\xampp\htdocs\Castores3`
2. Iniciar Apache y MySQL desde el panel de XAMPP
3. Importar la base de datos:
   - Abrir phpMyAdmin (`http://localhost/phpmyadmin`)
   - Crear la base de datos `castores`
   - Importar el archivo `BD/castores.sql`
4. Acceder al sistema: `http://localhost/Castores3`

### Credenciales por defecto
- **Usuario Admin:** verificar en la tabla `usuario` de la base de datos
- **Contraseña:** almacenada con hash — usar el registro inicial o crear un usuario desde phpMyAdmin

---

## Vistas Disponibles

| Vista | Descripción |
|-------|------------|
| `home` | Página principal (pública) |
| `Login` | Inicio de sesión |
| `DashboardRendimiento` | Dashboard de rendimiento del atleta |
| `Pruebas` | Registro y consulta de pruebas deportivas |
| `Partidos` | Historial de partidos del atleta |
| `Entrenamientos` | Sesiones de entrenamiento |
| `Consulta_atletas` | Listado y búsqueda de atletas |
| `Registro_atletas` | Formulario de registro de atletas |
| `Gestion_usuarios` | Administración de usuarios del sistema |
| `Noticias` | Publicación de noticias (pública) |
| `configuracion_cuotas` | Configuración de cuotas mensuales |
| `ReportesAdministrador` | Reportes y estadísticas administrativas |
| `DashboardRepresentante` | Panel del representante |
| `MiPerfil` | Perfil del usuario autenticado |

---

## Integraciones Externas

- **Tasa BCV** — Consulta de la tasa de cambio del Banco Central de Venezuela para conversiones en pagos con divisas
- **Bootstrap 5.3.2** (CDN) — Framework CSS para diseño responsivo
- **Bootstrap Icons 1.11.1** (CDN) — Librería de iconografía
- **Chart.js 4.4.0** (CDN) — Gráficas interactivas para dashboards

---

## Autor

**Enmanuel Castillo
**Abraham Camacho
**Clara Medina

** Proyecto de Trayecto 3, UPTAMCA

**Deportivo Los Castores** — Formando campeones dentro y fuera de la cancha.

Instagram: @deportivoloscastores | Facebook: Deportivo Los Castores FC | WhatsApp: +58 412-0000000
