
@echo off
SET hasta=0
SET fecha=%1
for /L %%N in (1,5,20) do (call :subroutine %%N)
GOTO :eof

:subroutine
 set /a hasta+=5
echo php artisan AoicollectorStat:export %1 %hasta% %fecha%

TIMEOUT /T 600 /nobreak > NUL
 GOTO :eof