@echo off
REM Activar entorno virtual Python
call "%~dp0..\Python\Scripts\activate.bat"

REM Ejecutar script de entrenamiento con 3 argumentos
python "%~dp0C_Model.py" %1 %2 %3