--
-- Use a specific schema and set it as default - thingy.
--
DROP SCHEMA IF EXISTS lbaw2464 CASCADE;
CREATE SCHEMA IF NOT EXISTS lbaw2464;
SET search_path TO  lbaw2464;

--
-- Drop any existing tables.
--
DROP TABLE IF EXISTS cards CASCADE;
DROP TABLE IF EXISTS items CASCADE;

-- Drop existing tables and types
DROP TABLE IF EXISTS attends, befriends, tags, files, comments, poll_votes, poll_options, polls, tickets, events, venues, users, roles CASCADE;
DROP DOMAIN IF EXISTS positive_integer CASCADE;
DROP TYPE IF EXISTS visibility1 CASCADE;
DROP TYPE IF EXISTS ticket_type CASCADE;

-- Define ENUM types
CREATE TYPE visibility1 AS ENUM ('public', 'private');
CREATE TYPE ticket_type AS ENUM ('regular', 'vip', 'student');

-- Define DOMAIN for positive integers
CREATE DOMAIN positive_integer AS INT CHECK (VALUE >= 0);



-- Roles table
CREATE TABLE roles (
    role_id SERIAL PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL
);

-- Users table
CREATE TABLE users (
    user_id SERIAL PRIMARY KEY,
    username VARCHAR(255) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    name VARCHAR(255) NOT NULL,
    phone VARCHAR(15) CHECK (phone ~ '^[0-9]{9,15}$'),
    profile_photo VARCHAR(255),
    password VARCHAR(255) NOT NULL,
    is_deleted BOOLEAN DEFAULT FALSE,
    role_id INT NOT NULL DEFAULT 2 REFERENCES roles(role_id) ON DELETE CASCADE
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
-- Venues table
CREATE TABLE venues (
    venue_id SERIAL PRIMARY KEY,
    name VARCHAR(255) UNIQUE NOT NULL,
    location VARCHAR(255) NOT NULL,
    max_capacity positive_integer
);

-- Events table
CREATE TABLE events (
    event_id SERIAL PRIMARY KEY,
    description TEXT NOT NULL,
    date TIMESTAMP NOT NULL CHECK (date >= CURRENT_DATE),
    postal_code VARCHAR(10),
    max_event_capacity positive_integer,
    country VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    visibility visibility1 NOT NULL,
    is_deleted BOOLEAN DEFAULT FALSE,
    venue_id INT REFERENCES venues(venue_id) ON DELETE CASCADE,
    organizer_id INT REFERENCES users(user_id) ON DELETE CASCADE
);

-- Tickets table
CREATE TABLE tickets (
    ticket_id SERIAL PRIMARY KEY,
    event_id INT REFERENCES events(event_id) ON DELETE CASCADE,
    type ticket_type NOT NULL,
    quantity positive_integer NOT NULL
);

-- Polls table
CREATE TABLE polls (
    poll_id SERIAL PRIMARY KEY,
    question TEXT NOT NULL,
    end_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP + interval '30 days',
    user_id INT REFERENCES users(user_id) ON DELETE CASCADE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- When the report was created
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
    event_id INT REFERENCES events(event_id) ON DELETE CASCADE
);

-- Poll options table
CREATE TABLE poll_options (
    option_id SERIAL PRIMARY KEY,
    poll_id INT REFERENCES polls(poll_id) ON DELETE CASCADE,
    option_text TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    votes INT DEFAULT 0
);


-- Poll votes table
CREATE TABLE poll_votes (
    vote_id SERIAL PRIMARY KEY,
    poll_id INT REFERENCES polls(poll_id) ON DELETE CASCADE,
    option_id INT REFERENCES poll_options(option_id) ON DELETE CASCADE,
    user_id INT REFERENCES users(user_id) ON DELETE CASCADE,
    CONSTRAINT unique_user_vote_per_poll UNIQUE (poll_id, user_id) -- Garante que um usuário só pode votar uma vez por enquete
);


-- Comments table
CREATE TABLE comments (
    comment_id SERIAL PRIMARY KEY,
    content TEXT NOT NULL,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_id INT REFERENCES users(user_id) ON DELETE CASCADE,
    event_id INT REFERENCES events(event_id) ON DELETE CASCADE
);

-- Files table
CREATE TABLE files (
    file_id SERIAL PRIMARY KEY,
    url VARCHAR(255) UNIQUE NOT NULL,
    comment_id INT REFERENCES comments(comment_id) ON DELETE CASCADE
);

-- Tags table
CREATE TABLE tags (
    tag_id SERIAL PRIMARY KEY,
    name VARCHAR(255) UNIQUE NOT NULL,
    event_id INT REFERENCES events(event_id) ON DELETE CASCADE
);

-- Befriends table (Many-to-Many between Users)
CREATE TABLE befriends (
    user_id_1 INT REFERENCES users(user_id) ON DELETE CASCADE,
    user_id_2 INT REFERENCES users(user_id) ON DELETE CASCADE,
    PRIMARY KEY (user_id_1, user_id_2)
);

-- Attends table (Many-to-Many between Users and Events)
CREATE TABLE attends (
    user_id INT REFERENCES users(user_id) ON DELETE CASCADE,
    event_id INT REFERENCES events(event_id) ON DELETE CASCADE,
    PRIMARY KEY (user_id, event_id),
    joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE invites (
    invite_id SERIAL PRIMARY KEY,
    event_id INT REFERENCES events(event_id) ON DELETE CASCADE,
    user_id INT REFERENCES users(user_id) ON DELETE CASCADE,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(50) DEFAULT 'pending' -- Example: 'pending', 'accepted', 'declined'
);

-- Reports table
CREATE TABLE reports (
    report_id SERIAL PRIMARY KEY, -- Unique identifier for each report
    event_id INT NOT NULL REFERENCES events(event_id) ON DELETE CASCADE, -- Links report to an event
    user_id INT NOT NULL REFERENCES users(user_id) ON DELETE CASCADE, -- User who submitted the report
    reason TEXT NOT NULL, -- Reason for the report
    r_status VARCHAR(50) DEFAULT 'pending', -- Status of the report: pending, reviewed, resolved
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- When the report was created
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- When the report was last updated
);


-- Set schema
SET search_path TO lbaw2464;

CREATE INDEX idx_reports_event_id ON reports (event_id);
CREATE INDEX idx_reports_user_id ON reports (user_id);
CREATE INDEX idx_reports_r_status ON reports (r_status);

-- Indexes for Users
-- Speed up lookups for authentication, profile queries, and friend relationships
CREATE INDEX idx_users_username ON users (username);
CREATE INDEX idx_users_email ON users (email);
CREATE INDEX idx_users_role_id ON users (role_id);

-- Indexes for Venues
-- Optimize venue searches by name and location
CREATE INDEX idx_venues_name ON venues (name);
CREATE INDEX idx_venues_location ON venues (location);

-- Indexes for Events
-- Optimize event searches by date, visibility, and venue
CREATE INDEX idx_events_date ON events (date);
CREATE INDEX idx_events_visibility ON events (visibility);
CREATE INDEX idx_events_venue_id ON events (venue_id);
CREATE INDEX idx_events_organizer_id ON events (organizer_id);

-- Full-text search index for event descriptions
CREATE INDEX idx_events_description ON events USING gin(to_tsvector('english', description));

-- Indexes for Tickets
-- Speed up queries for tickets by event and type
CREATE INDEX idx_tickets_event_id ON tickets (event_id);
CREATE INDEX idx_tickets_type ON tickets (type);

-- Indexes for Polls
-- Optimize lookups by event and user
CREATE INDEX idx_polls_event_id ON polls (event_id);
CREATE INDEX idx_polls_user_id ON polls (user_id);

-- Indexes for Poll Options
-- Optimize lookups by poll
CREATE INDEX idx_poll_options_poll_id ON poll_options (poll_id);

-- Indexes for Poll Votes
-- Optimize queries for votes by poll and user
CREATE INDEX idx_poll_votes_poll_id ON poll_votes (poll_id);
CREATE INDEX idx_poll_votes_user_id ON poll_votes (user_id);

-- Indexes for Comments
-- Optimize queries for comments by event and user
CREATE INDEX idx_comments_event_id ON comments (event_id);
CREATE INDEX idx_comments_user_id ON comments (user_id);

-- Full-text search index for comment content
CREATE INDEX idx_comments_content ON comments USING gin(to_tsvector('english', content));

-- Indexes for Files
-- Optimize lookups by comment (e.g., retrieving files attached to a comment)
CREATE INDEX idx_files_comment_id ON files (comment_id);

-- Indexes for Tags
-- Optimize tag searches by event
CREATE INDEX idx_tags_event_id ON tags (event_id);

-- Indexes for Befriends
-- Optimize lookups for friendships
CREATE INDEX idx_befriends_user_id_1 ON befriends (user_id_1);
CREATE INDEX idx_befriends_user_id_2 ON befriends (user_id_2);

-- Indexes for Attends
-- Optimize queries for event attendance and attendance lists
CREATE INDEX idx_attends_user_id ON attends (user_id);
CREATE INDEX idx_attends_event_id ON attends (event_id);

-- Set schema
SET search_path TO lbaw2464;

-- Trigger 1: Validate Event Date
-- Ensures that an event's date is set to the current or future date (BR05).
CREATE OR REPLACE FUNCTION validate_event_date()
RETURNS TRIGGER AS $$
BEGIN
    IF NEW.date < CURRENT_DATE THEN
        RAISE EXCEPTION 'Event date must be greater than or equal to the current date.';
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER event_date_validation
BEFORE INSERT OR UPDATE ON events
FOR EACH ROW
EXECUTE FUNCTION validate_event_date();


-- Trigger 2: Enforce Event Capacity
-- Ensures that the number of attendees does not exceed the event's maximum capacity (BR08).
CREATE OR REPLACE FUNCTION enforce_event_capacity()
RETURNS TRIGGER AS $$
DECLARE
    current_attendance INT;
BEGIN
    SELECT COUNT(*) INTO current_attendance
    FROM attends
    WHERE event_id = NEW.event_id;

    IF current_attendance >= (SELECT max_event_capacity FROM events WHERE event_id = NEW.event_id) THEN
        RAISE EXCEPTION 'The event has reached its maximum capacity.';
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER enforce_event_capacity
BEFORE INSERT ON attends
FOR EACH ROW
EXECUTE FUNCTION enforce_event_capacity();




-- Trigger 4: Cascade Delete User Content
-- Ensures that all related data is deleted when a user is removed (BR04).
CREATE OR REPLACE FUNCTION cascade_delete_user_content()
RETURNS TRIGGER AS $$
BEGIN
    DELETE FROM comments WHERE user_id = OLD.user_id;
    DELETE FROM tickets WHERE user_id = OLD.user_id;
    DELETE FROM polls WHERE user_id = OLD.user_id;
    DELETE FROM attends WHERE user_id = OLD.user_id;
    RETURN OLD;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER cascade_delete_user_content
AFTER DELETE ON users
FOR EACH ROW
EXECUTE FUNCTION cascade_delete_user_content();


-- Trigger 5: Restrict Comment Editing
-- Restricts comment editing to a 15-minute window after posting (BR05).
CREATE OR REPLACE FUNCTION restrict_comment_editing()
RETURNS TRIGGER AS $$
BEGIN
    IF EXTRACT(EPOCH FROM (CURRENT_TIMESTAMP - OLD.date)) > 900 THEN
        RAISE EXCEPTION 'You can only edit comments within 15 minutes of posting.';
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER restrict_comment_editing
BEFORE UPDATE ON comments
FOR EACH ROW
WHEN (OLD.content IS DISTINCT FROM NEW.content)
EXECUTE FUNCTION restrict_comment_editing();


-- Trigger 6: Auto-Generate Poll Results
-- Automatically calculate poll results when the poll ends (US30).
CREATE OR REPLACE FUNCTION auto_generate_poll_results()
RETURNS TRIGGER AS $$
BEGIN
    IF NEW.end_date < CURRENT_TIMESTAMP THEN
        -- Calculate results (This is an example; adjust according to logic)
        INSERT INTO poll_results (poll_id, option_id, votes)
        SELECT poll_id, option_id, COUNT(*) AS votes
        FROM poll_votes
        WHERE poll_id = NEW.poll_id
        GROUP BY poll_id, option_id;
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER auto_generate_poll_results
AFTER UPDATE ON polls
FOR EACH ROW
WHEN (NEW.end_date IS DISTINCT FROM OLD.end_date)
EXECUTE FUNCTION auto_generate_poll_results();


-- Trigger 7: Enforce Private Event Rules
-- Ensures private events are not shown in public searches (BR01).
CREATE OR REPLACE FUNCTION enforce_private_event_rules()
RETURNS TRIGGER AS $$
BEGIN
    IF NEW.visibility = 'private' THEN
        RAISE NOTICE 'This event is private and will not appear in public searches.';
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER enforce_private_event_rules
BEFORE INSERT OR UPDATE ON events
FOR EACH ROW
EXECUTE FUNCTION enforce_private_event_rules();


-- Trigger 8: Restrict Admin Participation
-- Prevents administrators from joining events as regular attendees (BR02).
CREATE OR REPLACE FUNCTION restrict_admin_participation()
RETURNS TRIGGER AS $$
BEGIN
    IF EXISTS (
        SELECT 1 FROM users
        WHERE user_id = NEW.user_id AND role_id = (SELECT role_id FROM roles WHERE name = 'admin')
    ) THEN
        RAISE EXCEPTION 'Administrators cannot join events as regular attendees.';
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER restrict_admin_participation
BEFORE INSERT ON attends
FOR EACH ROW
EXECUTE FUNCTION restrict_admin_participation();




-- Trigger 10: Prevent Duplicate Attendance
-- Ensures a user cannot join the same event multiple times (BR06).
CREATE OR REPLACE FUNCTION prevent_duplicate_attendance()
RETURNS TRIGGER AS $$
BEGIN
    IF EXISTS (
        SELECT 1 FROM attends
        WHERE user_id = NEW.user_id AND event_id = NEW.event_id
    ) THEN
        RAISE EXCEPTION 'User is already attending this event.';
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER prevent_duplicate_attendance
BEFORE INSERT ON attends
FOR EACH ROW
EXECUTE FUNCTION prevent_duplicate_attendance();

CREATE OR REPLACE FUNCTION update_report_timestamp()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trigger_update_report_timestamp
BEFORE UPDATE ON reports
FOR EACH ROW
EXECUTE FUNCTION update_report_timestamp();



-- Populate venues with sample data
INSERT INTO venues (name, location, max_capacity)
VALUES 
    ('Grand Concert Hall', '123 Main St, New York, NY', 1000),
    ('Sunset Theater', '456 Broadway Ave, Los Angeles, CA', 500),
    ('Riverfront Pavilion', '789 Riverside Dr, Chicago, IL', 1500),
    ('Starlight Arena', '101 Ocean View Rd, Miami, FL', 2000),
    ('Mountain View Amphitheater', '202 Hilltop Ln, Denver, CO', 800),
    ('Downtown Arts Center', '303 Center St, Austin, TX', 400),
    ('City Opera House', '404 Lincoln Sq, San Francisco, CA', 700),
    ('Seaside Auditorium', '505 Coastal Blvd, Seattle, WA', 1200),
    ('Skyline Plaza', '606 Skyline Dr, Atlanta, GA', 600),
    ('Historic Music Hall', '707 Heritage Way, Boston, MA', 1100);


-- Insert roles into the roles table if they do not already exist
DO $$
BEGIN
    IF NOT EXISTS (SELECT 1 FROM roles WHERE name = 'Admin') THEN
        INSERT INTO roles (role_id, name) VALUES (1, 'Admin');
    END IF;
    IF NOT EXISTS (SELECT 1 FROM roles WHERE name = 'User') THEN
        INSERT INTO roles (role_id, name) VALUES (2, 'User');
    END IF;
END $$;

-- Insert an admin user with a hashed password
INSERT INTO users (username, email, name, phone, profile_photo, password, is_deleted, role_id) 
VALUES ('admin', 'admin@example.com', 'admin', '1234567890', 'admin_photo.jpg', '$2y$10$7il/L2fNfgE4mYKQ1BMsQ.Pi6Fo58o.WOSoMhiHbGObXbep4qYbSK', FALSE, 1),
       ('user1', 'user1@example.com', 'User1', '1234567891', 'user1_photo.jpg', '$2y$10$7il/L2fNfgE4mYKQ1BMsQ.Pi6Fo58o.WOSoMhiHbGObXbep4qYbSK', FALSE, 2);

-- Insert 3 events from user1
INSERT INTO events (description, date, postal_code, max_event_capacity, country, name, visibility, is_deleted, venue_id, organizer_id)
VALUES 
    ('Event 1 Description', '2025-12-01 10:00:00', '12345', 100, 'USA', 'Event 1', 'public', FALSE, 1, 2),
    ('Event 2 Description', '2025-12-02 11:00:00', '12345', 200, 'USA', 'Event 2', 'public', FALSE, 2, 2),
    ('Event 3 Description', '2025-12-03 12:00:00', '12345', 300, 'USA', 'Event 3', 'public', FALSE, 3, 2);


INSERT INTO reports (event_id, user_id, reason, r_status)
VALUES 
    (1, 2, 'Reason for report 1', 'pending'),
    (2, 2, 'Reason for report 2', 'pending'),
    (3, 2, 'Reason for report 3', 'pending');