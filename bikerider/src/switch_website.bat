@echo off

rem Define the path to your Apache configuration file
set apache_conf="C:\Apache24\conf\httpd.conf"

rem Define the base path for your website directories
set websites_dir="C:\websites"

rem Define the name of the website directory you want to switch to
set website_name="bikerider"

rem Construct the full path to the website directory
set website_dir="C:\websites\bikerider\src"

rem Update the DocumentRoot and <Directory> directives in the Apache configuration file
findstr /C:"DocumentRoot" /C:"<Directory" %apache_conf% > nul
if %errorlevel% equ 0 (
    (echo DocumentRoot "%website_dir%"
    echo ^<Directory "%website_dir%"^>
    echo   Options Indexes FollowSymLinks
    echo   AllowOverride All
    echo   Require all granted
    echo ^</Directory^>) > temp.conf
    powershell -Command "(Get-Content %apache_conf%) | ForEach-Object {$_ -replace '<Directory.*>', '<Directory \"%website_dir%\">'} | Set-Content %apache_conf%"
    powershell -Command "(Get-Content %apache_conf%) | ForEach-Object {$_ -replace 'DocumentRoot.*', 'DocumentRoot \"%website_dir%\"'} | Set-Content %apache_conf%"
    del temp.conf
)

rem Restart the Apache service
net stop Apache2.4
net start Apache2.4

echo Website switched to %website_name%.
