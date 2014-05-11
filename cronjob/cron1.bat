@echo off

echo Spoton Cron Job 1

for /F "usebackq tokens=1,2 delims==" %%i in (`wmic os get LocalDateTime /VALUE 2^>NUL`) do if '.%%i.'=='.LocalDateTime.' set ldt=%%j
set ldt=%ldt:~0,4%-%ldt:~4,2%-%ldt:~6,2% %ldt:~8,2%:%ldt:~10,2%:%ldt:~12,6%
echo Execute C:\xampp\htdocs\synzecore\index.php cronjob index >> C:\xampp\htdocs\synzecore\cronjob\log1.txt
echo Local date is [%ldt%] >> C:\xampp\htdocs\synzecore\cronjob\log1.txt


C:\xampp\php\php.exe C:\xampp\htdocs\synzecore\index.php cronjob index >> C:\xampp\htdocs\synzecore\cronjob\log1.txt
REM pause


