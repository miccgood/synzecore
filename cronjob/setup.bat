@echo off
set STARTTIME=%TIME%

echo STARTTIME=%STARTTIME%
set /A HOUR1=(1%STARTTIME:~0,2%-100)
set /A MINUTE1=(1%STARTTIME:~3,2%-100+1)
set /A SECOND1=(1%STARTTIME:~6,2%-100)

if %HOUR1% LSS 10 set HOUR1=0%HOUR1%
if %MINUTE1% LSS 10 set MINUTE1=0%MINUTE1%

echo HOUR:MINUTE: %HOUR1%:%MINUTE1%

SCHTASKS /create /sc minute /mo 5 /tn "Spoton Cron Job 1" /tr C:\xampp\htdocs\synzecore\cronjob\cron1.bat /ST %HOUR1%:%MINUTE1%


set /A HOUR2=(1%STARTTIME:~0,2%-100)
set /A MINUTE2=(1%STARTTIME:~3,2%-100+6)
set /A SECOND2=(1%STARTTIME:~6,2%-100+10)

if %HOUR2% LSS 10 set HOUR2=0%HOUR2%
if %MINUTE2% LSS 10 set MINUTE2=0%MINUTE2%

echo HOUR:MINUTE: %HOUR2%:%MINUTE2%

SCHTASKS /create /sc minute /mo 5 /tn "Spoton Cron Job 2" /tr C:\xampp\htdocs\synzecore\cronjob\cron2.bat /ST %HOUR2%:%MINUTE2%
REM SCHTASKS /Create /SC MINUTE /MO 5 /ST 12:00

pause