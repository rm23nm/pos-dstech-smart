ALTER TABLE pelanggan ADD COLUMN password VARCHAR(255) NULL AFTER Email;
ALTER TABLE pelanggan ADD COLUMN remember_token VARCHAR(100) NULL AFTER password;
