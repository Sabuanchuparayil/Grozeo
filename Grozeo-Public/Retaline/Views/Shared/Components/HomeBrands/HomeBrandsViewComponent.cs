using Microsoft.AspNetCore.Mvc;
using Retaline.Core.Services.Catalog;
using System.Threading.Tasks;

namespace Retaline.Web.Views.Shared.Components.HomeBrands
{
    public class HomeBrandsViewComponent : ViewComponent
    {
        private readonly ICatalogService _catalogService;

        public HomeBrandsViewComponent(ICatalogService catalogService)
        {
            _catalogService = catalogService;
        }

        public async Task<IViewComponentResult> InvokeAsync()
        {
            var brandDetails = await _catalogService.GetBrandsForFooterMenu();
            return View(brandDetails);
        }
    }
}
