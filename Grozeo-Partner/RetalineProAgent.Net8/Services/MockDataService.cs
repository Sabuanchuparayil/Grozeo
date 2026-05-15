using RetalineProAgent.Models;

namespace RetalineProAgent.Services;

public static class MockDataService
{
    private static readonly Random _rand = new(42);

    public static List<OrderViewModel> GetOrders()
    {
        return new List<OrderViewModel>
        {
            new() { OrderNumber = "GRZ-2026-0001", CustomerName = "Rahul Sharma", CustomerEmail = "rahul@example.com", OrderDate = DateTime.Now.AddHours(-2), Total = 1249.00m, Status = "Processing", PaymentMethod = "UPI" },
            new() { OrderNumber = "GRZ-2026-0002", CustomerName = "Priya Patel", CustomerEmail = "priya@example.com", OrderDate = DateTime.Now.AddHours(-5), Total = 899.50m, Status = "Shipped", PaymentMethod = "Card" },
            new() { OrderNumber = "GRZ-2026-0003", CustomerName = "Amit Kumar", CustomerEmail = "amit@example.com", OrderDate = DateTime.Now.AddDays(-1), Total = 2450.00m, Status = "Delivered", PaymentMethod = "COD" },
            new() { OrderNumber = "GRZ-2026-0004", CustomerName = "Sneha Reddy", CustomerEmail = "sneha@example.com", OrderDate = DateTime.Now.AddDays(-1), Total = 675.00m, Status = "Delivered", PaymentMethod = "UPI" },
            new() { OrderNumber = "GRZ-2026-0005", CustomerName = "Vikram Singh", CustomerEmail = "vikram@example.com", OrderDate = DateTime.Now.AddDays(-2), Total = 3200.00m, Status = "Delivered", PaymentMethod = "Card" },
            new() { OrderNumber = "GRZ-2026-0006", CustomerName = "Anita Desai", CustomerEmail = "anita@example.com", OrderDate = DateTime.Now.AddDays(-2), Total = 1850.00m, Status = "Cancelled", PaymentMethod = "UPI" },
            new() { OrderNumber = "GRZ-2026-0007", CustomerName = "Rajesh Gupta", CustomerEmail = "rajesh@example.com", OrderDate = DateTime.Now.AddDays(-3), Total = 4500.00m, Status = "Delivered", PaymentMethod = "Card" },
            new() { OrderNumber = "GRZ-2026-0008", CustomerName = "Meera Iyer", CustomerEmail = "meera@example.com", OrderDate = DateTime.Now.AddDays(-3), Total = 999.00m, Status = "Delivered", PaymentMethod = "COD" },
        };
    }

    public static List<ProductViewModel> GetProducts()
    {
        return new List<ProductViewModel>
        {
            new() { Id = 1, SKU = "GRZ-RICE-001", Name = "Premium Basmati Rice 5kg", Category = "Groceries", Price = 450.00m, Stock = 150, Status = "Active" },
            new() { Id = 2, SKU = "GRZ-OIL-001", Name = "Sunflower Oil 1L", Category = "Groceries", Price = 175.00m, Stock = 85, Status = "Active" },
            new() { Id = 3, SKU = "GRZ-WHEAT-001", Name = "Whole Wheat Flour 10kg", Category = "Groceries", Price = 380.00m, Stock = 200, Status = "Active" },
            new() { Id = 4, SKU = "GRZ-DAL-001", Name = "Toor Dal 1kg", Category = "Groceries", Price = 140.00m, Stock = 5, Status = "Low Stock" },
            new() { Id = 5, SKU = "GRZ-SUGAR-001", Name = "Sugar 5kg", Category = "Groceries", Price = 225.00m, Stock = 0, Status = "Out of Stock" },
            new() { Id = 6, SKU = "GRZ-TEA-001", Name = "Premium Tea 500g", Category = "Beverages", Price = 320.00m, Stock = 75, Status = "Active" },
            new() { Id = 7, SKU = "GRZ-COFFEE-001", Name = "Filter Coffee 250g", Category = "Beverages", Price = 280.00m, Stock = 45, Status = "Active" },
            new() { Id = 8, SKU = "GRZ-SOAP-001", Name = "Bathing Soap Pack (4)", Category = "Personal Care", Price = 150.00m, Stock = 120, Status = "Active" },
            new() { Id = 9, SKU = "GRZ-DETG-001", Name = "Detergent Powder 2kg", Category = "Household", Price = 260.00m, Stock = 0, Status = "Out of Stock" },
            new() { Id = 10, SKU = "GRZ-MILK-001", Name = "Full Cream Milk 1L", Category = "Dairy", Price = 68.00m, Stock = 50, Status = "Active" },
            new() { Id = 11, SKU = "GRZ-BREAD-001", Name = "Brown Bread", Category = "Bakery", Price = 45.00m, Stock = 30, Status = "Active" },
            new() { Id = 12, SKU = "GRZ-EGG-001", Name = "Farm Eggs (12)", Category = "Dairy", Price = 85.00m, Stock = 100, Status = "Active" },
        };
    }

    public static List<SettlementViewModel> GetSettlements()
    {
        return new List<SettlementViewModel>
        {
            new() { SettlementNumber = "STL-2026-0015", Date = DateTime.Now.AddDays(-1), Amount = 45680.00m, Status = "Completed", BankAccount = "HDFC ****4521", UTR = "UTR123456789" },
            new() { SettlementNumber = "STL-2026-0014", Date = DateTime.Now.AddDays(-3), Amount = 32450.00m, Status = "Completed", BankAccount = "HDFC ****4521", UTR = "UTR123456788" },
            new() { SettlementNumber = "STL-2026-0013", Date = DateTime.Now.AddDays(-5), Amount = 28900.00m, Status = "Completed", BankAccount = "HDFC ****4521", UTR = "UTR123456787" },
            new() { SettlementNumber = "STL-2026-0016", Date = DateTime.Now, Amount = 18750.00m, Status = "Pending", BankAccount = "HDFC ****4521", UTR = "" },
            new() { SettlementNumber = "STL-2026-0012", Date = DateTime.Now.AddDays(-7), Amount = 51200.00m, Status = "Completed", BankAccount = "HDFC ****4521", UTR = "UTR123456786" },
        };
    }

    public static List<TicketViewModel> GetTickets()
    {
        return new List<TicketViewModel>
        {
            new() { TicketNumber = "TKT-0042", Subject = "Order not delivered", CustomerName = "Rahul Sharma", Priority = "High", Status = "Open", CreatedAt = DateTime.Now.AddHours(-3) },
            new() { TicketNumber = "TKT-0041", Subject = "Wrong item received", CustomerName = "Priya Patel", Priority = "Medium", Status = "In Progress", CreatedAt = DateTime.Now.AddHours(-8), UpdatedAt = DateTime.Now.AddHours(-2) },
            new() { TicketNumber = "TKT-0040", Subject = "Refund request", CustomerName = "Amit Kumar", Priority = "High", Status = "Pending", CreatedAt = DateTime.Now.AddDays(-1) },
            new() { TicketNumber = "TKT-0039", Subject = "Product quality issue", CustomerName = "Sneha Reddy", Priority = "Low", Status = "Resolved", CreatedAt = DateTime.Now.AddDays(-2), UpdatedAt = DateTime.Now.AddDays(-1) },
            new() { TicketNumber = "TKT-0038", Subject = "Payment failed but amount deducted", CustomerName = "Vikram Singh", Priority = "High", Status = "Escalated", CreatedAt = DateTime.Now.AddDays(-2) },
        };
    }

    public static List<CampaignViewModel> GetCampaigns()
    {
        return new List<CampaignViewModel>
        {
            new() { Id = 1, Name = "Summer Sale 2026", Type = "Discount", Status = "Active", StartDate = DateTime.Now.AddDays(-10), EndDate = DateTime.Now.AddDays(20), Budget = 50000, Spent = 18500, Impressions = 125000, Clicks = 4200, Conversions = 380 },
            new() { Id = 2, Name = "New User Signup Bonus", Type = "Referral", Status = "Active", StartDate = DateTime.Now.AddDays(-30), EndDate = DateTime.Now.AddDays(60), Budget = 25000, Spent = 8200, Impressions = 45000, Clicks = 2100, Conversions = 156 },
            new() { Id = 3, Name = "Diwali Special", Type = "Festival", Status = "Scheduled", StartDate = DateTime.Now.AddDays(120), EndDate = DateTime.Now.AddDays(135), Budget = 100000, Spent = 0, Impressions = 0, Clicks = 0, Conversions = 0 },
            new() { Id = 4, Name = "Flash Sale Friday", Type = "Flash Sale", Status = "Completed", StartDate = DateTime.Now.AddDays(-14), EndDate = DateTime.Now.AddDays(-13), Budget = 15000, Spent = 14800, Impressions = 85000, Clicks = 3500, Conversions = 520 },
        };
    }

    public static List<VehicleViewModel> GetVehicles()
    {
        return new List<VehicleViewModel>
        {
            new() { Id = 1, RegistrationNumber = "KA-01-AB-1234", Type = "Mini Van", Make = "Maruti", Model = "Eeco", Status = "Active", AssignedDriver = "Suresh Kumar" },
            new() { Id = 2, RegistrationNumber = "KA-01-CD-5678", Type = "Bike", Make = "Honda", Model = "Activa", Status = "Active", AssignedDriver = "Ramesh Patil" },
            new() { Id = 3, RegistrationNumber = "KA-01-EF-9012", Type = "Three Wheeler", Make = "Piaggio", Model = "Ape", Status = "Maintenance", AssignedDriver = "—" },
            new() { Id = 4, RegistrationNumber = "KA-01-GH-3456", Type = "Bike", Make = "TVS", Model = "Jupiter", Status = "Active", AssignedDriver = "Ganesh Rao" },
            new() { Id = 5, RegistrationNumber = "KA-01-IJ-7890", Type = "Tempo", Make = "Tata", Model = "Ace", Status = "Active", AssignedDriver = "Prakash Shetty" },
        };
    }

    public static List<DriverViewModel> GetDrivers()
    {
        return new List<DriverViewModel>
        {
            new() { Id = 1, Name = "Suresh Kumar", Phone = "+91 98765 43210", LicenseNumber = "KA-DL-2020-123456", Status = "On Duty", AssignedVehicle = "KA-01-AB-1234", TotalDeliveries = 1250, Rating = 4.8m },
            new() { Id = 2, Name = "Ramesh Patil", Phone = "+91 98765 43211", LicenseNumber = "KA-DL-2019-234567", Status = "On Duty", AssignedVehicle = "KA-01-CD-5678", TotalDeliveries = 980, Rating = 4.6m },
            new() { Id = 3, Name = "Ganesh Rao", Phone = "+91 98765 43212", LicenseNumber = "KA-DL-2021-345678", Status = "Available", AssignedVehicle = "KA-01-GH-3456", TotalDeliveries = 450, Rating = 4.9m },
            new() { Id = 4, Name = "Prakash Shetty", Phone = "+91 98765 43213", LicenseNumber = "KA-DL-2018-456789", Status = "On Duty", AssignedVehicle = "KA-01-IJ-7890", TotalDeliveries = 2100, Rating = 4.7m },
            new() { Id = 5, Name = "Mahesh Gowda", Phone = "+91 98765 43214", LicenseNumber = "KA-DL-2022-567890", Status = "Off Duty", AssignedVehicle = "—", TotalDeliveries = 120, Rating = 4.5m },
        };
    }

    public static List<CustomerViewModel> GetCustomers()
    {
        return new List<CustomerViewModel>
        {
            new() { Id = 1, Name = "Rahul Sharma", Email = "rahul@example.com", Phone = "+91 99887 76655", JoinedDate = DateTime.Now.AddMonths(-6), TotalOrders = 24, TotalSpent = 18500, Status = "Active" },
            new() { Id = 2, Name = "Priya Patel", Email = "priya@example.com", Phone = "+91 99887 76656", JoinedDate = DateTime.Now.AddMonths(-4), TotalOrders = 15, TotalSpent = 12200, Status = "Active" },
            new() { Id = 3, Name = "Amit Kumar", Email = "amit@example.com", Phone = "+91 99887 76657", JoinedDate = DateTime.Now.AddMonths(-8), TotalOrders = 42, TotalSpent = 35600, Status = "VIP" },
            new() { Id = 4, Name = "Sneha Reddy", Email = "sneha@example.com", Phone = "+91 99887 76658", JoinedDate = DateTime.Now.AddMonths(-2), TotalOrders = 8, TotalSpent = 5400, Status = "Active" },
            new() { Id = 5, Name = "Vikram Singh", Email = "vikram@example.com", Phone = "+91 99887 76659", JoinedDate = DateTime.Now.AddMonths(-12), TotalOrders = 65, TotalSpent = 52000, Status = "VIP" },
            new() { Id = 6, Name = "Anita Desai", Email = "anita@example.com", Phone = "+91 99887 76660", JoinedDate = DateTime.Now.AddDays(-15), TotalOrders = 2, TotalSpent = 1850, Status = "New" },
        };
    }

    public static List<LeadViewModel> GetLeads()
    {
        return new List<LeadViewModel>
        {
            new() { Id = 1, Name = "Kiran Enterprises", Email = "kiran@enterprise.com", Phone = "+91 98765 11111", Source = "Website", Status = "New", CreatedAt = DateTime.Now.AddHours(-4), AssignedTo = "Sales Team" },
            new() { Id = 2, Name = "Fresh Mart Store", Email = "contact@freshmart.com", Phone = "+91 98765 22222", Source = "Referral", Status = "Contacted", CreatedAt = DateTime.Now.AddDays(-2), AssignedTo = "John Doe" },
            new() { Id = 3, Name = "Quick Shop", Email = "hello@quickshop.in", Phone = "+91 98765 33333", Source = "Facebook", Status = "Qualified", CreatedAt = DateTime.Now.AddDays(-5), AssignedTo = "Jane Smith" },
            new() { Id = 4, Name = "Daily Needs Store", Email = "daily@needs.com", Phone = "+91 98765 44444", Source = "Google Ads", Status = "Proposal", CreatedAt = DateTime.Now.AddDays(-7), AssignedTo = "John Doe" },
            new() { Id = 5, Name = "Super Bazaar", Email = "info@superbazaar.com", Phone = "+91 98765 55555", Source = "Trade Show", Status = "Won", CreatedAt = DateTime.Now.AddDays(-14), AssignedTo = "Sales Team" },
        };
    }

    public static DashboardStatsViewModel GetDashboardStats()
    {
        var orders = GetOrders();
        var products = GetProducts();
        var customers = GetCustomers();
        var settlements = GetSettlements();
        var tickets = GetTickets();

        return new DashboardStatsViewModel
        {
            TotalRevenue = orders.Where(o => o.Status != "Cancelled").Sum(o => o.Total),
            TotalOrders = orders.Count,
            TotalProducts = products.Count,
            TotalCustomers = customers.Count,
            PendingSettlements = settlements.Where(s => s.Status == "Pending").Sum(s => s.Amount),
            OpenTickets = tickets.Count(t => t.Status != "Resolved"),
            RecentOrders = orders.Take(5).ToList(),
            LowStockProducts = products.Where(p => p.Stock <= 5).ToList()
        };
    }
}
