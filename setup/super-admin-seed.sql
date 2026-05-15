-- =============================================================
-- GROZEO SUPER ADMIN SEEDER
-- =============================================================
-- Super Admin: mail@jsabu.com / Admin@1234
-- Run this SQL against the shared MySQL database after setup.
-- =============================================================

-- -------------------------------------------------------
-- 1. BIZAPI / SCHEDULER - Customer table (retaline_customer)
--    Used for JWT-based API authentication
-- -------------------------------------------------------
INSERT INTO retaline_customer (
    cust_customer_name,
    cust_email,
    cust_mobile,
    cust_password,
    defaultRole,
    cust_status,
    cust_created_at,
    cust_updated_at
) VALUES (
    'Super Admin',
    'mail@jsabu.com',
    '0000000000',
    '$2y$12$B0.pt/95vfkPLAcuCZvFROMroytHlD4iLHfKolghxhaJ6HP3c4eA6',
    'superadmin',
    'active',
    NOW(),
    NOW()
) ON DUPLICATE KEY UPDATE
    cust_password = '$2y$12$B0.pt/95vfkPLAcuCZvFROMroytHlD4iLHfKolghxhaJ6HP3c4eA6',
    defaultRole = 'superadmin',
    cust_updated_at = NOW();

-- -------------------------------------------------------
-- 2. PARTNER - Azure SQL [User] table
--    Used for Forms Authentication in Partner portal
--    Run this against the Azure SQL tenant database
-- -------------------------------------------------------
-- NOTE: Run this on the Azure SQL Server (conn/localConnection)
-- The BCrypt hash below is for 'Admin@1234'
-- BCrypt hash: $2a$11$K7Hy6tFMZFBReOJfmLxfZuJqYXBfpKh1GjN8V5qVqK8FpXrMkczGa

/*
-- Azure SQL version:
IF NOT EXISTS (SELECT 1 FROM [User] WHERE Email = 'mail@jsabu.com')
BEGIN
    INSERT INTO [User] (
        usr_name,
        Email,
        Mobile,
        usr_password_hash,
        usr_role,
        usr_branch_id,
        IsActive,
        CreatedDate
    ) VALUES (
        'Super Admin',
        'mail@jsabu.com',
        '0000000000',
        '$2a$11$K7Hy6tFMZFBReOJfmLxfZuJqYXBfpKh1GjN8V5qVqK8FpXrMkczGa',
        'SuperAdmin',
        0,
        1,
        GETDATE()
    );
END
ELSE
BEGIN
    UPDATE [User] SET
        usr_password_hash = '$2a$11$K7Hy6tFMZFBReOJfmLxfZuJqYXBfpKh1GjN8V5qVqK8FpXrMkczGa',
        usr_role = 'SuperAdmin',
        IsActive = 1
    WHERE Email = 'mail@jsabu.com';
END
*/

-- -------------------------------------------------------
-- 3. LEGACY PHP (Bizadmin / Manage-Products)
--    Uses the same retaline_customer table as Bizapi
--    The INSERT in step 1 covers this.
-- -------------------------------------------------------

-- -------------------------------------------------------
-- 4. GROZEO-PUBLIC (ASP.NET Core)
--    Uses the same retaline_customer table via Bizapi API
--    No separate user table — authenticates through Bizapi.
-- -------------------------------------------------------

-- =============================================================
-- IMPORTANT: After running this seeder:
-- 1. Change the default password immediately in production
-- 2. The password hash above is for: Admin@1234
-- 3. For the Partner Azure SQL, uncomment and run the block
--    against the Azure SQL database separately
-- =============================================================
