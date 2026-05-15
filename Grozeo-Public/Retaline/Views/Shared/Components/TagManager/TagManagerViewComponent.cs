using Microsoft.AspNetCore.Mvc;
using Retaline.Core.BusinessModel.UserDetails;
using Retaline.Core.Services.Authentication;
using Retaline.Web.Models.Info;
using System.Collections.Generic;
using Retaline.Core.Services.Home;
using SaasKit.Multitenancy;
using System.Threading.Tasks;

namespace Retaline.Web.Views.Shared.Components.UserDetails
{
    public class TagManagerViewComponent : ViewComponent
    {
        private readonly IHomePageService _homePageService;
        private readonly Core.ViewModel.Tenant.AppTenant _tenant;

        public TagManagerViewComponent(IHomePageService homePageService, ITenant<Core.ViewModel.Tenant.AppTenant> tenant)
        {
            _homePageService = homePageService;
            this._tenant = tenant?.Value;
        }

        public async Task<IViewComponentResult> InvokeAsync(bool isHeader)
        {
            IList<Retaline.Core.BusinessModel.Home.RetalinePlugin> _tenantPlugins = await _homePageService.GetTenantPlugins(_tenant.Id);

            PluginModel pluginModel = new PluginModel();
            pluginModel.TenantPlugins = _tenantPlugins;
            pluginModel.isHeader = isHeader;
            return View(pluginModel);
        }
    }
}
