@echo off
title TrackXa - Local Development Server
color 0A

echo.
echo  ████████╗██████╗  █████╗  ██████╗██╗  ██╗██╗  ██╗ █████╗ 
echo     ██╔══╝██╔══██╗██╔══██╗██╔════╝██║ ██╔╝╚██╗██╔╝██╔══██╗
echo     ██║   ██████╔╝███████║██║     █████╔╝  ╚███╔╝ ███████║
echo     ██║   ██╔══██╗██╔══██║██║     ██╔═██╗  ██╔██╗ ██╔══██║
echo     ██║   ██║  ██║██║  ██║╚██████╗██║  ██╗██╔╝ ██╗██║  ██║
echo     ╚═╝   ╚═╝  ╚═╝╚═╝  ╚═╝ ╚═════╝╚═╝  ╚═╝╚═╝  ╚═╝╚═╝  ╚═╝
echo.
echo  Starting TrackXa Local Server...
echo  ─────────────────────────────────────────────
echo  Website :  http://localhost:8080
echo  Admin   :  http://localhost:8080/admin/login
echo  Install :  http://localhost:8080/install.php
echo  API Docs:  http://localhost:8080/api/docs
echo  ─────────────────────────────────────────────
echo  Press CTRL+C to stop the server
echo.

cd /d "%~dp0public"
php -S localhost:8080 router.php

pause
