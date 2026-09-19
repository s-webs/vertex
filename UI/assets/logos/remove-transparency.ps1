Add-Type -AssemblyName System.Drawing

$logoDirectory = [System.IO.Path]::GetFullPath($PSScriptRoot)
$logoFiles = Get-ChildItem -LiteralPath $logoDirectory -Filter 'adilet-lift-*.png' -File

foreach ($logoFile in $logoFiles) {
    $source = [System.Drawing.Image]::FromFile($logoFile.FullName)
    try {
        $flattened = New-Object System.Drawing.Bitmap($source.Width, $source.Height, [System.Drawing.Imaging.PixelFormat]::Format24bppRgb)
        try {
            $canvas = [System.Drawing.Graphics]::FromImage($flattened)
            try {
                $canvas.Clear([System.Drawing.Color]::Black)
                $canvas.DrawImage($source, 0, 0, $source.Width, $source.Height)
            }
            finally {
                $canvas.Dispose()
            }

            $temporaryPath = [System.IO.Path]::Combine($logoDirectory, "$($logoFile.BaseName)-opaque.png")
            $flattened.Save($temporaryPath, [System.Drawing.Imaging.ImageFormat]::Png)
        }
        finally {
            $flattened.Dispose()
        }
    }
    finally {
        $source.Dispose()
    }

    Move-Item -LiteralPath $temporaryPath -Destination $logoFile.FullName -Force
}

$logoFiles | ForEach-Object {
    $check = [System.Drawing.Bitmap]::FromFile($_.FullName)
    try {
        [PSCustomObject]@{
            File = $_.Name
            Width = $check.Width
            Height = $check.Height
            PixelFormat = $check.PixelFormat
        }
    }
    finally {
        $check.Dispose()
    }
}
