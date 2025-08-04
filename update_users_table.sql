ALTER TABLE users ADD COLUMN role ENUM('doctor', 'patient') NOT NULL DEFAULT 'patient';
