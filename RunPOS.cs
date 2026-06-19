using System;
using System.Diagnostics;
using System.IO;

class Program
{
    static void Main()
    {
        string batFile = Path.Combine(AppDomain.CurrentDomain.BaseDirectory, "start_pos.bat");
        
        if (File.Exists(batFile))
        {
            ProcessStartInfo psi = new ProcessStartInfo();
            psi.FileName = "cmd.exe";
            psi.Arguments = "/c \"" + batFile + "\"";
            psi.WindowStyle = ProcessWindowStyle.Hidden;
            psi.CreateNoWindow = true;
            psi.UseShellExecute = false;
            
            Process.Start(psi);
        }
        else
        {
            Console.WriteLine("File start_pos.bat tidak ditemukan!");
        }
    }
}
