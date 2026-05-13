INSERT IGNORE INTO permission (id, PermissionName, Link, Icon, Level, MenuInduk, SubMenu, `Order`, Status, isSuperAdmin, created_at, updated_at) 
VALUES (114, 'Customer Display Queue', 'customerdisplay', 'fas fa-desktop', 3, 25, 2, 28.3, 1, 0, NOW(), NOW());

INSERT IGNORE INTO permissionrole (RoleID, PermissionID, created_at, updated_at) 
SELECT id, 114, NOW(), NOW() FROM roles WHERE RoleName IN ('SuperAdmin', 'Admin', 'Kasir', 'CASHIER');
