--
-- Use a specific schema and set it as default - thingy.
--
DROP SCHEMA IF EXISTS thingy CASCADE;
CREATE SCHEMA IF NOT EXISTS thingy;
SET search_path TO thingy;

--
-- Drop any existing tables.
--
DROP TABLE IF EXISTS users CASCADE;
DROP TABLE IF EXISTS cards CASCADE;
DROP TABLE IF EXISTS items CASCADE;
DROP TABLE IF EXISTS venues CASCADE;
DROP TABLE IF EXISTS events CASCADE;

--
-- Create tables.
--
CREATE TABLE users (
  id SERIAL PRIMARY KEY,
  name VARCHAR NOT NULL,
  email VARCHAR UNIQUE NOT NULL,
  password VARCHAR NOT NULL,
  remember_token VARCHAR
);

CREATE TABLE cards (
  id SERIAL PRIMARY KEY,
  name VARCHAR NOT NULL,
  user_id INTEGER REFERENCES users NOT NULL
);

CREATE TABLE items (
  id SERIAL PRIMARY KEY,
  card_id INTEGER NOT NULL REFERENCES cards ON DELETE CASCADE,
  description VARCHAR NOT NULL,
  done BOOLEAN NOT NULL DEFAULT FALSE
);

CREATE TABLE venues (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    location VARCHAR(255) NOT NULL,
    max_capacity INT NOT NULL
);
CREATE TABLE events (
    event_id SERIAL PRIMARY KEY,
    description TEXT NOT NULL,
    date TIMESTAMP NOT NULL,
    postal_code VARCHAR(10),
    max_event_capacity INT NOT NULL,
    country VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    visibility BOOLEAN NOT NULL,
    venue_id INT REFERENCES venues(id) ON DELETE CASCADE,
    organizer_id INT REFERENCES users(id) ON DELETE CASCADE
);

--
-- Insert value.
--

INSERT INTO users VALUES (
  DEFAULT,
  'John Doe',
  'admin@example.com',
  '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W'
); -- Password is 1234. Generated using Hash::make('1234')

INSERT INTO cards VALUES (DEFAULT, 'Things to do', 1);
INSERT INTO items VALUES (DEFAULT, 1, 'Buy milk');
INSERT INTO items VALUES (DEFAULT, 1, 'Walk the dog', true);

INSERT INTO cards VALUES (DEFAULT, 'Things not to do', 1);
INSERT INTO items VALUES (DEFAULT, 2, 'Break a leg');
INSERT INTO items VALUES (DEFAULT, 2, 'Crash the car');

-- Insert sample venues
INSERT INTO venues (name, location, max_capacity)
VALUES 
    ('Conference Center', 'Downtown Avenue, City A', 500),
    ('Community Hall', 'Main Street, City B', 200),
    ('Outdoor Park', 'Park Lane, City C', 1000);

-- Insert sample events
INSERT INTO events (description, date, postal_code, max_event_capacity, country, name, visibility, venue_id, organizer_id)
VALUES
    ('Tech Conference 2024', '2024-12-15 10:00:00', '12345', 300, 'Country A', 'Future of Tech', TRUE, 1, 1),
    ('Charity Run', '2024-11-25 08:00:00', '54321', 500, 'Country B', 'Run for Hope', TRUE, 3, 1),
    ('Private Wedding', '2024-06-10 14:00:00', '67890', 150, 'Country C', 'Wedding Celebration', FALSE, 2, 1);
