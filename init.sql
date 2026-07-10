DROP TABLE IF EXISTS users;

-- Crea la tabla
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);