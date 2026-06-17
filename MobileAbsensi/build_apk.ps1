$ErrorActionPreference = "Stop"

$jdkUrl = "https://api.adoptium.net/v3/binary/latest/17/ga/windows/x64/jdk/hotspot/normal/eclipse"
$zipPath = "jdk17.zip"
$extractPath = "jdk17"

if (-Not (Test-Path $extractPath)) {
    Write-Host "Downloading JDK 17..."
    Invoke-WebRequest -Uri $jdkUrl -OutFile $zipPath
    Write-Host "Extracting JDK 17..."
    Expand-Archive -Path $zipPath -DestinationPath $extractPath -Force
}

# Find the exact jdk folder name inside extractPath
$jdkDir = Get-ChildItem -Path $extractPath -Directory | Select-Object -First 1
$env:JAVA_HOME = $jdkDir.FullName
$env:PATH = "$env:JAVA_HOME\bin;" + $env:PATH

Write-Host "Java version:"
java -version

Write-Host "Building APK..."
.\gradlew assembleDebug
