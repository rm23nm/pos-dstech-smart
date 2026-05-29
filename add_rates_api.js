const fs = require('fs');
const file = 'd:/OneDrive/My Project Aplikasi/Aplikasi Gate Acses DStechsmart/backend/index.js';
let content = fs.readFileSync(file, 'utf8');

const endpoints = `
// GET Parking Rates
app.get('/api/parking/rates', async (req, res) => {
    try {
        const tenant_id = req.headers['tenant-id'] || 1;
        const [rows] = await db.query('SELECT * FROM access_parking_rates WHERE tenant_id = ?', [tenant_id]);
        res.json(rows);
    } catch (error) {
        res.status(500).json({ error: error.message });
    }
});

// UPDATE Parking Rates
app.post('/api/parking/rates', async (req, res) => {
    try {
        const { vehicle_type, base_rate, hourly_rate } = req.body;
        const tenant_id = req.headers['tenant-id'] || 1;
        
        // Cek apakah sudah ada rate
        const [existing] = await db.query('SELECT * FROM access_parking_rates WHERE tenant_id = ? AND vehicle_type = ?', [tenant_id, vehicle_type]);
        if (existing.length > 0) {
            await db.query('UPDATE access_parking_rates SET base_rate = ?, hourly_rate = ? WHERE tenant_id = ? AND vehicle_type = ?', 
            [base_rate, hourly_rate, tenant_id, vehicle_type]);
        } else {
            await db.query('INSERT INTO access_parking_rates (tenant_id, vehicle_type, base_rate, hourly_rate) VALUES (?, ?, ?, ?)', 
            [tenant_id, vehicle_type, base_rate, hourly_rate]);
        }
        res.json({ success: true, message: 'Tarif ' + vehicle_type + ' berhasil diperbarui' });
    } catch (error) {
        res.status(500).json({ error: error.message });
    }
});

const PORT = process.env.PORT || 5000;
`;

content = content.replace('const PORT = process.env.PORT || 5000;', endpoints);

fs.writeFileSync(file, content);
console.log("Added rates endpoints to index.js");
