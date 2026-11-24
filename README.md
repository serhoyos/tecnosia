# TECNOSIA

Proyecto aplicado de grado — Tecnología en Desarrollo de Software, Universidad Nacional Abierta y a Distancia (UNAD), 2026.

## Descripción

Plataforma web para la evaluación preliminar de ideas de negocio. Utiliza PHP, arquitectura Modelo-Vista-Controlador (MVC), persistencia relacional y un motor determinístico de reglas (IdeaEvaluator V2.5). Genera un puntaje de 1 a 10, un veredicto, observaciones, riesgos y recomendaciones. No verifica hipótesis con clientes ni garantiza viabilidad comercial.

## Requisitos

- Windows y XAMPP con Apache, PHP (con PDO MySQL y mbstring) y MariaDB (estructura exportada de MariaDB 10.4.32).
- Apache con mod_rewrite habilitado y permisos para reglas de reescritura.
- Git, o descarga del repositorio como ZIP.
- Acceso administrativo local a MariaDB para crear una base de datos vacía y un usuario de aplicación.

## Instalación local

1. Clonar el repositorio desde una terminal en `C:\xampp\htdocs` (o extraer el ZIP en una carpeta `tecnosia`).

   ```bash
   git clone https://github.com/serhoyos/tecnosia.git
   ```

2. Iniciar Apache y MariaDB desde XAMPP. Ejecutar `schema.sql` en Workbench o phpMyAdmin, sobre una instalación nueva sin tablas de TECNOSIA. El archivo no contiene datos de prueba ni elimina tablas existentes.
3. Crear un usuario local de base de datos con una contraseña propia y permisos SELECT, INSERT, UPDATE y DELETE sobre `tecnosia.*`. Usar una cuenta administrativa solo para la instalación.
4. Copiar `config/config.example.php` como `config/config.php`. Completar las constantes DB_HOST, DB_NAME, DB_USER, DB_PASS, DB_CHARSET y URL_BASE. No publicar el archivo privado.
5. Configurar Apache para servir la carpeta `public/` como raíz del sitio. La siguiente configuración es una propuesta para un host virtual local; requiere probarla en la instalación de destino.

   En `C:\xampp\apache\conf\extra\httpd-vhosts.conf`:

   ```apache
   <VirtualHost *:80>
       ServerName tecnosia.local
       DocumentRoot "C:/xampp/htdocs/tecnosia/public"
       <Directory "C:/xampp/htdocs/tecnosia/public">
           AllowOverride None
           Require local
           RewriteEngine On
           RewriteCond %{REQUEST_FILENAME} !-f
           RewriteCond %{REQUEST_FILENAME} !-d
           RewriteRule ^ index.php [L,QSA]
       </Directory>
   </VirtualHost>
   ```

   Confirmar que Apache carga el archivo de hosts virtuales y mod_rewrite. Con permisos de administrador, agregar `127.0.0.1 tecnosia.local` al archivo `C:\Windows\System32\drivers\etc\hosts`. Reiniciar Apache. Ajustar rutas si XAMPP está instalado en otra ubicación.
6. Configurar `URL_BASE` como `http://tecnosia.local/` y abrir esa dirección en el navegador. Registrar un usuario mediante el formulario; no hay cuentas predefinidas.

## Funcionalidades

- Registro, inicio y cierre de sesión.
- Creación, consulta, edición y eliminación de ideas propias.
- Generación y actualización de diagnósticos mediante reglas.
- Visualización de resultados en el dashboard.

## Organización

- `app/Controllers/`: controladores.
- `app/Core/`: conexión PDO y enrutamiento.
- `app/Models/`: persistencia de ideas y diagnósticos.
- `app/Services/IdeaEvaluator.php`: motor de evaluación.
- `views/`: interfaces.
- `public/index.php`: punto de entrada.
- `config/config.example.php`: plantilla de configuración.
- `schema.sql`: estructura de las tablas users, ideas y diagnostico.

## Comprobación funcional

Registrar dos usuarios de prueba, iniciar sesión, crear una idea, revisar el diagnóstico, editar la idea y comprobar la actualización. Eliminar una idea propia de prueba. Comprobar que el formulario de edición de una idea ajena no esté disponible y que el acceso sin sesión redirija al inicio de sesión.

## Alcance y limitaciones

El diagnóstico es heurístico y depende de la descripción y el sector registrados. Los umbrales y ponderaciones no han sido validados empíricamente. La aplicación conserva el diagnóstico vigente, no un historial de evaluaciones. La versión se orienta a desarrollo local; no constituye una aplicación auditada ni preparada para producción.

## Autor

Sergio Alain Hoyos Álvarez — UNAD.
