Set WshShell = CreateObject("WScript.Shell")
WshShell.CurrentDirectory = "C:\Users\ALI.A.SALAH\Desktop\pos"

' Kill any old PHP
Dim colProc, objProc
On Error Resume Next
Set colProc = GetObject("winmgmts:\\.\root\cimv2").ExecQuery("SELECT * FROM Win32_Process WHERE Name = 'php.exe'")
For Each objProc In colProc
    objProc.Terminate
Next
On Error GoTo 0

WScript.Sleep 2000

' Start server hidden
WshShell.Run "cmd /c ""C:\Program Files\php-8.5.9\php.exe"" artisan serve --host=0.0.0.0 --port=8000 > nul 2>&1", 0, False
