Set WshShell = CreateObject("WScript.Shell")
WshShell.CurrentDirectory = "C:\Users\ALI.A.SALAH\Desktop\pos"

' Kill old PHP processes
Dim colProc, objProc
Set colProc = GetObject("winmgmts:\\.\root\cimv2").ExecQuery("SELECT * FROM Win32_Process WHERE Name = 'php.exe'")
For Each objProc In colProc
    objProc.Terminate
Next
WScript.Sleep 2000

' Start server
WshShell.Run "cmd /c ""C:\Program Files\php-8.5.9\php.exe"" artisan serve --host=0.0.0.0 --port=8080", 1, False

' Wait and verify
WScript.Sleep 5000
MsgBox "POS Server started on http://192.168.0.108:8080" & vbCrLf & vbCrLf & "Other devices access: http://192.168.0.108:8080" & vbCrLf & "Login: admin@pos.local / password", vbInformation, "POS Server"
