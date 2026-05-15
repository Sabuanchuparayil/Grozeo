using System;
using System.IO;
using System.Threading.Tasks;
using Microsoft.AspNetCore.Mvc;
using Microsoft.AspNetCore.Http;
using Microsoft.Extensions.Logging;
using Newtonsoft.Json;

namespace DataEntry
{
    public static class AddCostCentreEntries
    {
        public static async Task<IActionResult> Run(HttpRequest req, ILogger log)
        {
            log.LogInformation("C# HTTP trigger function AddCostCentreEntries processed a request.");

            string requestBody = await new StreamReader(req.Body).ReadToEndAsync();
            CostCentreLogData data = new CostCentreLogData();
            try
            {
                data = JsonConvert.DeserializeObject<CostCentreLogData>(requestBody, new JsonSerializerSettings { PreserveReferencesHandling = PreserveReferencesHandling.Objects });
            }
            catch (Exception ex)
            {
                string strex = ex.Message;
                return new OkObjectResult(new Result { statusId = ResultType.Exception, message = " Exception : " + strex });
            }

            Result res = DataService.AddCostCentreEntries(data,log);
            
            return new OkObjectResult(res);
        }
    }
}
