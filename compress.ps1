Add-Type -AssemblyName System.Drawing
$image = [System.Drawing.Image]::FromFile("C:\Users\glenn\OneDrive\Desktop\Capstone\mbc-student-monitoring-system\public\images\school_background.png")
$image.Save("C:\Users\glenn\OneDrive\Desktop\Capstone\mbc-student-monitoring-system\public\images\school_background_compressed.jpg", [System.Drawing.Imaging.ImageFormat]::Jpeg)
$image.Dispose()
