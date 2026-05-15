# Partner Web.config — Credentials Reference

All credential values in Web.config have been blanked. Fill them in during deployment.

## Required Keys (Web.config appSettings)

| Key | Description | Where to Get |
|-----|-------------|-------------|
| `user` | API authentication username | Internal |
| `psw` | API authentication password | Internal |
| `blobConnectionstring` | Azure Blob Storage connection string | Azure Portal > Storage Account > Access Keys |
| `blobContainer` | Azure Blob container name | Azure Portal > Storage Account > Containers |
| `blobURL` | Azure Blob base URL | `https://{account}.blob.core.windows.net/` |
| `api.url` | Bizapi base URL | Your Bizapi deployment URL |
| `partner.url` | Partner portal URL | Your Partner deployment URL |
| `grozeo.url` | Public store URL | Your public site URL |
| `admin.url` | Backoffice URL | Your admin deployment URL |
| `api.DefaultDB` | MySQL database name | Your DB name |
| `SendGridAPIKey` | SendGrid email API key | https://sendgrid.com > Settings > API Keys |
| `FSSAIAPIKey` | FSSAI verification API key | https://emptra.com |
| `FSSAIAPIclientId` | FSSAI client ID | https://emptra.com |
| `FinascopAPIUrl` | Finascop service URL | Your finascop deployment URL |
| `FinascopAPIKey` | Finascop API key | Internal |
| `google_client_id` | Google OAuth client ID | Google Cloud Console > Credentials |
| `google_client_secret` | Google OAuth secret | Google Cloud Console > Credentials |
| `google_redirect_url` | Google OAuth redirect | `https://partner.yourdomain.com/login` |
| `googleAPIKey` | Google Maps API key | Google Cloud Console > APIs |
| `googleDescriptionKey` | Google Gemini API key | https://aistudio.google.com |
| `Facebook_AppId` | Facebook app ID | https://developers.facebook.com |
| `Facebook_AppSecret` | Facebook app secret | https://developers.facebook.com |
| `Recaptcha.Secret` | reCAPTCHA secret | https://www.google.com/recaptcha/admin |
| `Recaptcha.Key` | reCAPTCHA site key | https://www.google.com/recaptcha/admin |
| `emptra.clientid` | Emptra client ID | https://emptra.com |
| `emptra.secret` | Emptra secret | https://emptra.com |
| `AWS_Key_ID` | AWS access key ID | AWS IAM Console |
| `AWS_Secret` | AWS secret access key | AWS IAM Console |
| `AWS_Region` | AWS region | e.g., `ap-south-1` |
| `AWS_S3_BucketName` | S3 uploads bucket | AWS S3 Console |
| `AWS_S3_BucketProducts` | S3 products bucket | AWS S3 Console |
| `IdealPostcodeKey` | UK postcode lookup key | https://ideal-postcodes.co.uk |
| `POSTCODEKeyUK` | Postcode Anywhere key | https://www.postcodeanywhere.co.uk |
| `VATSTACKApiSecret` | VAT validation key | https://vatstack.com |
| `MatomoUrl` | Matomo analytics URL | Your Matomo instance |
| `MatomoToken` | Matomo admin token | Matomo > Settings > API |
| `RedisConnectionString` | Redis connection string | Azure Portal > Redis Cache > Access Keys |
| `SurepassAPIToken` | Surepass verification token | https://surepass.io |
| `PaymentGatewaykey` | Payment gateway public key | Your payment gateway dashboard |
| `TawkToPropertyId` | Tawk.to property ID | https://www.tawk.to |
| `TawkToWidgetId` | Tawk.to widget ID | https://www.tawk.to |
| `TawkToSiteAPIKey` | Tawk.to API key | https://www.tawk.to |
| `GetAddressIOAPIKey` | GetAddress.io API key | https://getaddress.io |
| `VoxbayPIN` | Voxbay telephony PIN | Voxbay portal |
| `VoxbayUID` | Voxbay user ID | Voxbay portal |
| `KeyVaultName` | Azure Key Vault name | Azure Portal |

## Connection Strings

| Name | Description | Format |
|------|-------------|--------|
| `conn` | Azure SQL (tenant DB) | `Data Source=tcp:{server},1433;Initial Catalog={db};User Id={user};Password={pass}` |
| `localConnection` | Azure SQL (same as conn) | Same as above |
| `mySqlConnection` | MySQL (shared DB) | `Server={host};Database={db};Uid={user};Pwd={pass};SslMode=required;Convert Zero Datetime=True` |
| `FinascopConnection` | Azure SQL (finascop DB) | Same format as conn |
