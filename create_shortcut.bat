@echo off
setlocal enabledelayedexpansion

:: Set the title of the window
title Absence Management Shortcut Creator

:: Get the current directory
set "CURRENT_DIR=%~dp0"
set "CURRENT_DIR=%CURRENT_DIR:~0,-1%"

:: Get the desktop path
set "DESKTOP=%USERPROFILE%\Desktop"

:: Create the shortcut
echo Creating desktop shortcut...
powershell "$WS = New-Object -ComObject WScript.Shell; $SC = $WS.CreateShortcut('%DESKTOP%\Absence Management.lnk'); $SC.TargetPath = 'cmd.exe'; $SC.Arguments = '/k cd /d "%CURRENT_DIR%" && start http://localhost:8000 && start cmd /k "npm run dev" && php artisan serve'; $SC.WorkingDirectory = '%CURRENT_DIR%'; $SC.IconLocation = '%CURRENT_DIR%\public\favicon.ico'; $SC.Save()"

:: Check if the shortcut was created successfully
if exist "%DESKTOP%\Absence Management.lnk" (
    echo.
    echo Shortcut created successfully!
    echo.
    echo Instructions:
    echo 1. Double-click the "Absence Management" shortcut on your desktop
    echo 2. The application will start automatically
    echo 3. Your default browser will open to http://localhost:8000
    echo.
    echo Note: Make sure you have PHP, Composer, and Node.js installed on your system.
    echo.
    pause
) else (
    echo.
    echo Error: Failed to create the shortcut.
    echo Please make sure you have administrator privileges.
    echo.
    pause
) 