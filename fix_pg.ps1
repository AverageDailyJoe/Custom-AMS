$env:PGPASSWORD = "123"
psql -U postgres -d ams_dev -c "GRANT ALL ON SCHEMA public TO ams_user;"
if ($LASTEXITCODE -eq 0) { Write-Host "GRANT_OK" } else { Write-Host "GRANT_FAIL code=$LASTEXITCODE" }
