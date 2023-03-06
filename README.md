<p align="center">
Ditech-Test

Brayan Julian Rodriguez Moreno
Bogotá - Colombia
Developer PHP
</p>

## ENTORNO DE DESARROLLO LOCAL:

- [PHP v8.1.10](https://www.php.net/downloads.php).
- [Composer v2.5.1](https://getcomposer.org/changelog/2.5.1).
- [Laravel Framework v10.2.0](https://laravel.com/docs/10.x/installation).
- [MySql v8.0.30](https://dev.mysql.com/downloads/installer/).
- [Visual Studio Code v1.74.3](https://code.visualstudio.com/download).
- [Git v2.39.0.windows.2](https://git-scm.com/download/win).


## INSTALACIÓN LOCAL:

1.	Descargue el repositorio https://github.com/b-jrm/ditech-test/

2.	En la raíz de la carpeta ubique el archivo .env.example y cámbiele el nombre por .env 

3.	Cree la base de datos en su gestor local con el nombre ditech-test con la collation utf8mb4_unicode_ci preferiblemente

4.	Ingrese en la ubicación de la carpeta desde la terminal CMD, y ejecute los siguientes comandos:


- composer update
- php artisan key:generate
- php artisan migrate --seed
- php artisan serve --port=8000



