
@echo off
SET hasta=0
SET fecha_desde=%3
SET fecha_hasta=%4
for /L %%N in (1,5,20) do (call :subroutine %%N)
GOTO :eof

:subroutine
 set /a hasta+=5
php artisan AoicollectorStat:export %1 %hasta% %fecha_desde% %fecha_hasta%

TIMEOUT /T 10 /nobreak > NUL
 GOTO :eof