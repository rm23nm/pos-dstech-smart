use xpos;
INSERT IGNORE INTO subscriptiondetail (NoTransaksi, NoUrut, PermissionID, created_at, updated_at)
SELECT NoTransaksi, 99, 113, NOW(), NOW() FROM (
    SELECT '2002' as NoTransaksi UNION SELECT '2003' UNION SELECT '2022' UNION 
    SELECT 'PFNB001' UNION SELECT 'PFNB002' UNION SELECT 'PFNB003'
) as pkts;
