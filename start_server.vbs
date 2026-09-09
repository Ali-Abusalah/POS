Set WshShell = CreateObject("WScript.Shell")
WshShell.CurrentDirectory = "C:\Users\ALI.A.SALAH\Desktop\pos"
WshShell.Run "cmd /c ""C:\Program Files\php-8.5.9\php.exe"" artisan serve --host=0.0.0.0 --port=8080", 0, False
Set WshShell = Nothing
