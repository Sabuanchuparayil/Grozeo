using Microsoft.AspNetCore.Mvc;
using Retaline.Core.BusinessModel.UserDetails;
using Retaline.Core.Services.ProfileManagement;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace Retaline.Web.Views.Shared.Components.CustomerAddress
{
    public class AddressListViewComponent : ViewComponent
    {
        private readonly IProfileService _profileService;
        public AddressListViewComponent(IProfileService profileService)
        {
            _profileService = profileService;
        }
        public async Task<IViewComponentResult> InvokeAsync()
        {
            var details = await _profileService.GetAddress();
            if (details != null && details.Data != null)
            {
                return View("AddressList", details.Data.OrderByDescending(a => a.IsPrimary).ThenByDescending(a => a.BranchId).ToList());
            }
            return View("AddressList", new List<Address>());

        }
    }
}
