# Sistema de Asistencia de Personal - Morfeo S. A.

Este proyecto, desarrollado en *Laravel*, está diseñado para gestionar la asistencia del personal de Morfeo S.A. El sistema facilita el registro y control de la asistencia de los empleados, abarcando el monitoreo de ausencias, la administración de días de vacaciones y el registro de ausencias no justificadas.

## Características

- Registro de días de vacaciones por empleado basado en la antigüedad.
- Control de ausencias justificadas (licencias médicas, maternidad/paternidad).
- Reportes de asistencia y ausencias de empleados.

## Tecnologías utilizadas

El proyecto utiliza las siguientes tecnologías y librerías:

- **Laravel**: Framework de PHP para el desarrollo web.
- **Carbon**: Librería para la manipulación de fechas y horas.
- **Spatie Laravel Permission**: Gestión de permisos y roles.
- **Laravel Sanctum**: Autenticación de API.
- **PHPUnit**: Framework para pruebas unitarias.

## Instalación

Sigue los pasos a continuación para configurar el proyecto en tu entorno local:

1. **Clonar el repositorio**

   ```bash
   git clone https://github.com/EmilianoBlazco/morfeo-b.git
   cd morfeo-b
   ```

2. **Instalar dependencias**

   Utiliza el siguiente comando para instalar todas las dependencias del proyecto.

   ```bash
   composer install
   ```

3. **Configurar las variables de entorno**

   Crea un archivo `.env` en el directorio raíz del proyecto y define las variables de entorno necesarias. Puedes copiar el archivo `.env.example` y modificarlo según tus necesidades.

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Migrar la base de datos**

   Ejecuta las migraciones para crear las tablas necesarias en la base de datos.

   ```bash
   php artisan migrate
   ```

5. **Iniciar el servidor de desarrollo**

   Para iniciar el servidor de desarrollo, usa el siguiente comando:

   ```bash
   php artisan serve
   ```

6. **Acceder a la aplicación**

   Una vez iniciado el servidor, abre tu navegador y ve a [http://localhost:8000](http://localhost:8000) para acceder a la aplicación.

## Autores del Proyecto

- Blazco Emiliano Nahuel
- Cristaldo Yonathan Ariel

## Licencia

Este proyecto está bajo la Licencia GNU Affero General Public License (AGPL)
