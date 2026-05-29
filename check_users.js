require('dotenv').config();
const mysql = require('mysql2/promise');
(async () => {
    const db = await mysql.createConnection({
        host: process.env.DB_HOST,
        user: process.env.DB_USER,
        password: process.env.DB_PASSWORD,
        database: process.env.DB_NAME
    });
    const [users] = await db.query('SELECT * FROM access_users');
    console.log(users);
    db.end();
})();
