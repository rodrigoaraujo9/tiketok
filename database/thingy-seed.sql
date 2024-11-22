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
  username VARCHAR(255) UNIQUE NOT NULL,
  email VARCHAR(255) UNIQUE NOT NULL,
  name VARCHAR(255) NOT NULL,
  phone VARCHAR(15) CHECK (phone ~ '^[0-9]{9,15}$'),
  profile_photo VARCHAR(255),
  password VARCHAR(255) NOT NULL,
  is_admin BOOLEAN DEFAULT FALSE,
  is_deleted BOOLEAN DEFAULT FALSE
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

-- Insert sample venues
INSERT INTO venues (name, location, max_capacity)
VALUES 
    ('Conference Center', 'Downtown Avenue, City A', 500),
    ('Community Hall', 'Main Street, City B', 200),
    ('Outdoor Park', 'Park Lane, City C', 1000);
