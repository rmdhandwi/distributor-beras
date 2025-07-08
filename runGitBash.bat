@echo off
setlocal

:: Ambil path folder saat ini
set FOLDER_PATH=%CD%

:: Path ke Git Bash
set GIT_BASH_PATH="D:\Git\git-bash.exe"

start "" %GIT_BASH_PATH% --cd="%FOLDER_PATH%"
