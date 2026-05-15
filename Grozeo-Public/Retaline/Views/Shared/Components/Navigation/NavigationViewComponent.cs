using Microsoft.AspNetCore.Mvc;
using Microsoft.Extensions.Configuration;
using Retaline.Core.Services.Catalog;
using Retaline.Core.ViewModel.Home;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace Retaline.Web.Views.Shared.Components.Navigation
{
    public class NavigationViewComponent : ViewComponent
    {
        private readonly ICatalogService _catalogService;
        public NavigationViewComponent(ICatalogService catalogService)
        {
            _catalogService = catalogService;
        }
        public async Task<IViewComponentResult> InvokeAsync()
        {
            var catalog = await _catalogService.GetCatalog();

            return View("Navigation", catalog);
        }
    }
}
