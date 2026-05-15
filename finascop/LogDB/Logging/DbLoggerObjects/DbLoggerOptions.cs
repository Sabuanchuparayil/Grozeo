using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace LogDB.Logging.DbLoggerObjects
{
    public class DbLoggerOptions
    {
        private string _connectionString;

        public string ConnectionString
        {
            get => _connectionString;
            set
            {
                _connectionString = value ?? "";
                if (!_connectionString.Contains("Encrypt", StringComparison.OrdinalIgnoreCase))
                    _connectionString += ";Encrypt=false";
                if (!_connectionString.Contains("TrustServerCertificate", StringComparison.OrdinalIgnoreCase))
                    _connectionString += ";TrustServerCertificate=true";
            }
        }

        public string[] LogFields { get; set; }

        public string LogTable { get; set; }

        public DbLoggerOptions()
        {
        }
    }
}
