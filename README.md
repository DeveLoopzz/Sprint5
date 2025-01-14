# API MHArmory

## 📋 Requisitos Previos

Asegúrate de tener instalados:

- **PHP >= 8.1**
- **Composer**
- **MySQL**
- **Node.js y npm** (para compilación de assets si aplica)
- **Postman o similar** (para pruebas de la API)

## 🚀 Instalación

1. Clona el repositorio:

   ```bash
   git clone https://github.com/tuusuario/tu-proyecto.git
   cd tu-proyecto
   ```
   
2. Instalar dependencias:

   ```bash
   composer install
   ```
3. Copiar el archivo .env.example.

4. Instalar laravel passport:
   ```bash
   php artisan passport:install
   ```
5. Generar claves passport
   ```bash
   php artisan passport:client --personal
   ```
6.Usuarios para postman:
   Admin:
      email : test@example.com
      password : 12345678
   Hunter:
      email : test@hunter.com
      password : 12345678
