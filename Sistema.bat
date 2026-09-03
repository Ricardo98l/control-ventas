@echo off
title Iniciando Sistema de Cargas...
color 0A

echo ==========================================
echo    SISTEMA DE CARGAS - DAKER IT
echo ==========================================
echo.

:: 1. Ir a la carpeta del proyecto
cd C:\xampp\htdocs\control-ventas\control-ventas

echo [1/4] Buscando nuevas actualizaciones en la nube...
git pull origin main

echo.
echo [2/4] Iniciando Motor de Base de Datos...
:: Detenemos cualquier base de datos pegada previamente para evitar errores
taskkill /F /IM mysqld.exe > NUL 2>&1

:: Encendemos MySQL directo desde la consola (sin interfaz grafica)
start /MIN C:\xampp\mysql_start.bat

:: Le damos 5 segundos exactos para que el motor arranque internamente
timeout /t 5 /nobreak > NUL

echo.
echo [3/4] Aplicando cambios en la base de datos...
C:\xampp\php\php.exe artisan migrate --force

echo.
echo [4/4] Abriendo el sistema...
start http://127.0.0.1:8000

echo.
echo El sistema esta en linea. POR FAVOR NO CIERRE ESTA VENTANA NEGRA.
echo.

:: Levanta el servidor local de Laravel
C:\xampp\php\php.exe artisan serve