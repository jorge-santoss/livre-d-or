ALTER TABLE users 
ADD role ENUM('admin', 'moderateur', 'utilisateur', 'visiteur') NOT NULL DEFAULT 'visiteur';
