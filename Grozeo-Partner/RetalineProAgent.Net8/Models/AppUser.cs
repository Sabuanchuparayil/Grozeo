namespace RetalineProAgent.Models;

public class AppUser
{
    public int usr_id { get; set; }
    public string usr_name { get; set; } = string.Empty;
    public string usr_email { get; set; } = string.Empty;
    public string usr_role { get; set; } = string.Empty;
    public int usr_branch_id { get; set; }
    public string usr_password_hash { get; set; } = string.Empty;
    public int usr_status { get; set; }
    public DateTime? usr_created_at { get; set; }
    public DateTime? usr_updated_at { get; set; }
}
