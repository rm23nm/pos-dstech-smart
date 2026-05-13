use xpos;
INSERT INTO permission (id, PermissionName, Link, Icon, Level, MenuInduk, SubMenu, `Order`, Status, isSuperAdmin, created_at, updated_at) 
VALUES (113, 'Info Kitchen', 'infokitchen', 'fas fa-utensils', 3, 25, 2, 28.2, 1, 0, NOW(), NOW());

INSERT INTO subscriptiondetail (NoTransaksi, NoUrut, PermissionID, created_at, updated_at)
VALUES ('2003', (SELECT COALESCE(MAX(NoUrut),0)+1 FROM (SELECT * FROM subscriptiondetail) as sd where NoTransaksi = '2003'), 113, NOW(), NOW());

INSERT INTO permissionrole (PermissionID, RoleID, RecordOwnerID, created_at, updated_at)
SELECT 113, id, RecordOwnerID, NOW(), NOW() FROM roles WHERE RoleName IN ('SuperAdmin', 'Admin', 'Kasir');
