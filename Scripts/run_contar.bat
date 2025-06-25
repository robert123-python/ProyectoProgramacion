@echo off
REM %1: ruta imagen
REM %2: ruta modelo
REM %3: etiquetas separadas por coma
call "%~dp0..\Python\Scripts\activate.bat"
python "%~dp0contar.py" %1 %2 %3