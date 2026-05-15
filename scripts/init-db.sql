-- Grozeo Platform - Database Initialization
-- Creates tables and seeds initial admin user

-- User master table (used by Partner Portal)
CREATE TABLE IF NOT EXISTS `finascop_usr_master` (
    `usr_id` INT AUTO_INCREMENT PRIMARY KEY,
    `usr_name` VARCHAR(100) NOT NULL,
    `usr_email` VARCHAR(255) NOT NULL UNIQUE,
    `usr_role` VARCHAR(50) NOT NULL DEFAULT 'TenantAdmin',
    `usr_branch_id` INT NOT NULL DEFAULT 0,
    `usr_password_hash` VARCHAR(255) NOT NULL,
    `usr_status` TINYINT NOT NULL DEFAULT 1,
    `usr_created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `usr_updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_email_status` (`usr_email`, `usr_status`),
    INDEX `idx_role` (`usr_role`),
    INDEX `idx_branch` (`usr_branch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Role reference table
CREATE TABLE IF NOT EXISTS `sys_role` (
    `RoleId` INT AUTO_INCREMENT PRIMARY KEY,
    `RoleName` VARCHAR(50) NOT NULL,
    `Description` VARCHAR(255),
    `IsActive` TINYINT NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed roles
INSERT IGNORE INTO `sys_role` (`RoleId`, `RoleName`, `Description`) VALUES
(1, 'SuperAdmin', 'Full platform access'),
(2, 'TenantAdmin', 'Store owner - manages store, orders, products'),
(3, 'Finance', 'Financial reports, settlements, ledger'),
(4, 'Support', 'Customer support tickets and knowledge base');

-- Seed SuperAdmin user: mail@jsabu.com / Grozeo@2024
INSERT INTO `finascop_usr_master` (`usr_name`, `usr_email`, `usr_role`, `usr_branch_id`, `usr_password_hash`, `usr_status`)
VALUES ('Sabu Admin', 'mail@jsabu.com', 'SuperAdmin', 1, '$2b$12$76T/L2.Fk7j0gtepVwjdb.d498hGhbFKOXhTKnu68BEgroSD7YtkK', 1)
ON DUPLICATE KEY UPDATE `usr_name` = VALUES(`usr_name`), `usr_role` = VALUES(`usr_role`), `usr_password_hash` = VALUES(`usr_password_hash`);

-- Seed demo users for other roles (password: Grozeo@2024)
INSERT INTO `finascop_usr_master` (`usr_name`, `usr_email`, `usr_role`, `usr_branch_id`, `usr_password_hash`, `usr_status`)
VALUES ('Tenant Demo', 'tenant@grozeo.in', 'TenantAdmin', 1, '$2b$12$76T/L2.Fk7j0gtepVwjdb.d498hGhbFKOXhTKnu68BEgroSD7YtkK', 1)
ON DUPLICATE KEY UPDATE `usr_name` = VALUES(`usr_name`);

INSERT INTO `finascop_usr_master` (`usr_name`, `usr_email`, `usr_role`, `usr_branch_id`, `usr_password_hash`, `usr_status`)
VALUES ('Finance Demo', 'finance@grozeo.in', 'Finance', 1, '$2b$12$76T/L2.Fk7j0gtepVwjdb.d498hGhbFKOXhTKnu68BEgroSD7YtkK', 1)
ON DUPLICATE KEY UPDATE `usr_name` = VALUES(`usr_name`);

INSERT INTO `finascop_usr_master` (`usr_name`, `usr_email`, `usr_role`, `usr_branch_id`, `usr_password_hash`, `usr_status`)
VALUES ('Support Demo', 'support@grozeo.in', 'Support', 1, '$2b$12$76T/L2.Fk7j0gtepVwjdb.d498hGhbFKOXhTKnu68BEgroSD7YtkK', 1)
ON DUPLICATE KEY UPDATE `usr_name` = VALUES(`usr_name`);
