@echo off

rem Get the path of the batch file's directory
set "batchDir=%~dp0"

rem Change to the batch file's directory
cd /d "%batchDir%"

rem Perform git fetch and git pull
git fetch
git pull

rem Pause (optional) to keep the console window open
pause
