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
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- Poll votes table
CREATE TABLE poll_votes (
    vote_id SERIAL PRIMARY KEY,
    poll_id INT REFERENCES polls(poll_id) ON DELETE CASCADE,
    option_id INT REFERENCES poll_options(option_id) ON DELETE CASCADE,
    user_id INT REFERENCES users(user_id) ON DELETE CASCADE
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
    ('Historic Music Hall', '707 Heritage Way, Boston, MA', 1100),  
    ('Altice Arena', 'Lisbon', 20000),
    ('Coliseu dos Recreios', 'Lisbon', 4000),
    ('Casa da Música', 'Porto', 1300),
    ('Teatro Nacional São João', 'Porto', 750),
('Centro Cultural de Belém', 'Lisbon', 1500),
('Pavilhão Rosa Mota', 'Porto', 8000),
('Estádio da Luz', 'Lisbon', 65000),
('Estádio do Dragão', 'Porto', 52000),
('Campo Pequeno', 'Lisbon', 9000),
('Teatro Municipal Rivoli', 'Porto', 1200),
('Super Bock Arena', 'Porto', 8000),
('Fórum Braga', 'Braga', 3000),
('Teatro Garcia de Resende', 'Évora', 700),
('Centro de Artes e Espetáculos', 'Figueira da Foz', 800),
('Coliseu do Porto', 'Porto', 3500),
('Altice Forum Braga', 'Braga', 4000),
('Auditório Pedro Almodóvar', 'Lisbon', 1500),
('Praça de Touros', 'Lisbon', 10000),
('Lagoa Municipal Auditorium', 'Lagoa', 1200),
('Casino Estoril', 'Estoril', 1500),
('Porto Palácio Congress Hotel', 'Porto', 1200);




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
       ('user1', 'user1@example.com', 'User1', '1234567891', 'user1_photo.jpg', '$2y$10$7il/L2fNfgE4mYKQ1BMsQ.Pi6Fo58o.WOSoMhiHbGObXbep4qYbSK', FALSE, 2),
       ('user2', 'user2@example.com', 'Maria Santos', '912345672', 'user2_photo.jpg', '$2y$10$7il/L2fNfgE4mYKQ1BMsQ.Pi6Fo58o.WOSoMhiHbGObXbep4qYbSK', FALSE, 2),
('user3', 'user3@example.com', 'João Oliveira', '912345673', 'user3_photo.jpg', '$2y$10$7il/L2fNfgE4mYKQ1BMsQ.Pi6Fo58o.WOSoMhiHbGObXbep4qYbSK', FALSE, 2),
('user4', 'user4@example.com', 'Ana Martins', '912345674', 'user4_photo.jpg', '$2y$10$7il/L2fNfgE4mYKQ1BMsQ.Pi6Fo58o.WOSoMhiHbGObXbep4qYbSK', FALSE, 2),
('user5', 'user5@example.com', 'Pedro Silva', '912345675', 'user5_photo.jpg', '$2y$10$7il/L2fNfgE4mYKQ1BMsQ.Pi6Fo58o.WOSoMhiHbGObXbep4qYbSK', FALSE, 2),
('user6', 'user6@example.com', 'Rita Costa', '912345676', 'user6_photo.jpg', '$2y$10$7il/L2fNfgE4mYKQ1BMsQ.Pi6Fo58o.WOSoMhiHbGObXbep4qYbSK', FALSE, 2),
('user7', 'user7@example.com', 'Carlos Gonçalves', '912345677', 'user7_photo.jpg', '$2y$10$7il/L2fNfgE4mYKQ1BMsQ.Pi6Fo58o.WOSoMhiHbGObXbep4qYbSK', FALSE, 2),
('user8', 'user8@example.com', 'Paula Almeida', '912345678', 'user8_photo.jpg', '$2y$10$7il/L2fNfgE4mYKQ1BMsQ.Pi6Fo58o.WOSoMhiHbGObXbep4qYbSK', FALSE, 2),
('user9', 'user9@example.com', 'Miguel Fernandes', '912345679', 'user9_photo.jpg', '$2y$10$7il/L2fNfgE4mYKQ1BMsQ.Pi6Fo58o.WOSoMhiHbGObXbep4qYbSK', FALSE, 2),
('user10', 'user10@example.com', 'Inês Correia', '912345680', 'user10_photo.jpg', '$2y$10$7il/L2fNfgE4mYKQ1BMsQ.Pi6Fo58o.WOSoMhiHbGObXbep4qYbSK', FALSE, 2),
('user11', 'user11@example.com', 'André Rodrigues', '912345681', 'user11_photo.jpg', '$2y$10$7il/L2fNfgE4mYKQ1BMsQ.Pi6Fo58o.WOSoMhiHbGObXbep4qYbSK', FALSE, 2),
('user12', 'user12@example.com', 'Cláudia Sousa', '912345682', 'user12_photo.jpg', '$2y$10$7il/L2fNfgE4mYKQ1BMsQ.Pi6Fo58o.WOSoMhiHbGObXbep4qYbSK', FALSE, 2),
('user13', 'user13@example.com', 'Tiago Pinto', '912345683', 'user13_photo.jpg', '$2y$10$7il/L2fNfgE4mYKQ1BMsQ.Pi6Fo58o.WOSoMhiHbGObXbep4qYbSK', FALSE, 2),
('user14', 'user14@example.com', 'Sara Melo', '912345684', 'user14_photo.jpg', '$2y$10$7il/L2fNfgE4mYKQ1BMsQ.Pi6Fo58o.WOSoMhiHbGObXbep4qYbSK', FALSE, 2),
('user15', 'user15@example.com', 'José Neves', '912345685', 'user15_photo.jpg', '$2y$10$7il/L2fNfgE4mYKQ1BMsQ.Pi6Fo58o.WOSoMhiHbGObXbep4qYbSK', FALSE, 2),
('user16', 'user16@example.com', 'Sofia Nunes', '912345686', 'user16_photo.jpg', '$2y$10$7il/L2fNfgE4mYKQ1BMsQ.Pi6Fo58o.WOSoMhiHbGObXbep4qYbSK', FALSE, 2),
('user17', 'user17@example.com', 'Luís Carvalho', '912345687', 'user17_photo.jpg', '$2y$10$7il/L2fNfgE4mYKQ1BMsQ.Pi6Fo58o.WOSoMhiHbGObXbep4qYbSK', FALSE, 2),
('user18', 'user18@example.com', 'Joana Moreira', '912345688', 'user18_photo.jpg', '$2y$10$7il/L2fNfgE4mYKQ1BMsQ.Pi6Fo58o.WOSoMhiHbGObXbep4qYbSK', FALSE, 2),
('user19', 'user19@example.com', 'Manuel Teixeira', '912345689', 'user19_photo.jpg', '$2y$10$7il/L2fNfgE4mYKQ1BMsQ.Pi6Fo58o.WOSoMhiHbGObXbep4qYbSK', FALSE, 2),
('user20', 'user20@example.com', 'Beatriz Figueiredo', '912345690', 'user20_photo.jpg', '$2y$10$7il/L2fNfgE4mYKQ1BMsQ.Pi6Fo58o.WOSoMhiHbGObXbep4qYbSK', FALSE, 2),
('user21', 'user21@example.com', 'Ricardo Antunes', '912345691', 'user21_photo.jpg', '$2y$10$7il/L2fNfgE4mYKQ1BMsQ.Pi6Fo58o.WOSoMhiHbGObXbep4qYbSK', FALSE, 2),
('user22', 'user22@example.com', 'Patrícia Araújo', '912345692', 'user22_photo.jpg', '$2y$10$7il/L2fNfgE4mYKQ1BMsQ.Pi6Fo58o.WOSoMhiHbGObXbep4qYbSK', FALSE, 2),
('user23', 'user23@example.com', 'Hugo Ribeiro', '912345693', 'user23_photo.jpg', '$2y$10$7il/L2fNfgE4mYKQ1BMsQ.Pi6Fo58o.WOSoMhiHbGObXbep4qYbSK', FALSE, 2),
('user24', 'user24@example.com', 'Carolina Mendes', '912345694', 'user24_photo.jpg', '$2y$10$7il/L2fNfgE4mYKQ1BMsQ.Pi6Fo58o.WOSoMhiHbGObXbep4qYbSK', FALSE, 2),
('user25', 'user25@example.com', 'Filipe Pires', '912345695', 'user25_photo.jpg', '$2y$10$7il/L2fNfgE4mYKQ1BMsQ.Pi6Fo58o.WOSoMhiHbGObXbep4qYbSK', FALSE, 2);

-- Insert 3 events from user1
-- Events with future dates
-- Insert events into the events table using only existing venues
INSERT INTO events (description, date, postal_code, max_event_capacity, country, name, visibility, is_deleted, venue_id, organizer_id)
VALUES 
    ('Event 1 Description', '2030-12-01 10:00:00', '12345', 100, 'USA', 'Event 1', 'public', FALSE, 1, 2),
    ('Event 2 Description', '2030-12-02 11:00:00', '12345', 200, 'USA', 'Event 2', 'public', FALSE, 2, 2),
    ('Event 3 Description', '2030-12-03 12:00:00', '12345', 300, 'USA', 'Event 3', 'public', FALSE, 3, 2),
    ('Concert at Altice Arena', '2030-12-30 20:00:00', '1990-001', 20000, 'Portugal', 'Rock Night', 'public', FALSE, 11, 2),
    ('Art Exhibition at Casa da Música', '2030-11-25 15:00:00', '4099-002', 1300, 'Portugal', 'Abstract World', 'private', FALSE, 13, 2),
    ('Stand-up Comedy Show at Coliseu dos Recreios', '2030-12-15 18:30:00', '1000-078', 4000, 'Portugal', 'Laugh Out Loud', 'public', FALSE, 12, 3),
    ('Jazz Night at Pavilhão Rosa Mota', '2030-12-20 21:00:00', '4050-234', 8000, 'Portugal', 'Smooth Jazz Evening', 'public', FALSE, 16, 4),
    ('Tech Conference at Fórum Braga', '2031-01-15 10:00:00', '4700-340', 3000, 'Portugal', 'Future of AI', 'public', FALSE, 22, 5),
    ('Food Festival at Campo Pequeno', '2031-03-20 12:00:00', '1049-063', 9000, 'Portugal', 'Taste of Lisbon', 'public', FALSE, 19, 6),
    ('Music Festival at Estádio da Luz', '2031-06-25 19:00:00', '1500-311', 65000, 'Portugal', 'Summer Vibes Festival', 'public', FALSE, 17, 2),
    ('Book Fair at Centro Cultural de Belém', '2031-04-12 09:00:00', '1449-003', 1500, 'Portugal', 'Books and Beyond', 'public', FALSE, 15, 3),
    ('Charity Gala at Estádio do Dragão', '2031-05-01 19:30:00', '4350-415', 52000, 'Portugal', 'Hope for Tomorrow', 'private', FALSE, 18, 4),
    ('Sports Event at Pavilhão Rosa Mota', '2031-02-10 15:00:00', '4050-234', 8000, 'Portugal', 'Championship Finals', 'public', FALSE, 16, 5),
    ('Theater Play at Teatro Nacional São João', '2031-01-18 20:00:00', '4000-295', 750, 'Portugal', 'Shakespeare Reimagined', 'public', FALSE, 14, 2),
    ('Art Workshop at Centro de Artes e Espetáculos', '2031-02-22 14:00:00', '3080-073', 800, 'Portugal', 'Creative Minds', 'private', FALSE, 24, 6),
    ('Film Screening at Teatro Municipal Rivoli', '2031-03-14 17:00:00', '4000-420', 1200, 'Portugal', 'Cinema Classics Night', 'public', FALSE, 20, 4),
    ('Pop Concert at Super Bock Arena', '2031-07-10 20:00:00', '4050-378', 8000, 'Portugal', 'Pop Explosion', 'public', FALSE, 21, 3),
    ('Jazz Evening at Casa da Música', '2031-05-05 21:00:00', '4099-002', 1300, 'Portugal', 'Night of Jazz', 'public', FALSE, 13, 5),
    ('Dance Festival at Estádio da Luz', '2031-08-15 16:00:00', '8005-226', 30000, 'Portugal', 'Dance the Night Away', 'public', FALSE, 17, 6),
    ('Wine Tasting Event at Casino Estoril', '2031-03-22 18:00:00', '2765-190', 1500, 'Portugal', 'A Taste of Excellence', 'private', FALSE, 30, 2),
    ('Rock Concert at Coliseu do Porto', '2031-04-14 19:30:00', '4000-161', 3500, 'Portugal', 'Rock Legends', 'public', FALSE, 26, 4),
    ('Charity Run at Altice Arena', '2031-09-20 09:00:00', '4700-035', 20000, 'Portugal', 'Run for a Cause', 'public', FALSE, 11, 3),
    ('Historical Play at Coliseu dos Recreios', '2031-03-10 20:00:00', '4000-450', 1000, 'Portugal', 'History Comes Alive', 'private', FALSE, 12, 5);

-- Comments with future dates
INSERT INTO comments (content, date, user_id, event_id) 
VALUES 
    ('Amazing concert! Loved every moment.', '2030-12-31 10:00:00', 2, 1),
    ('The event was well organized.', '2030-12-31 11:00:00', 3, 2),
    ('Great atmosphere and friendly people.', '2031-01-01 14:00:00', 4, 3),
    ('Looking forward to the next event.', '2031-01-02 16:00:00', 5, 4),
    ('Not happy with the event, could be better.', '2031-01-03 18:00:00', 6, 5),
    ('Loved the food and entertainment!', '2031-01-04 20:00:00', 7, 6),
    ('The venue was fantastic.', '2031-01-05 12:00:00', 8, 7),
    ('The event exceeded my expectations.', '2031-01-06 15:00:00', 9, 8),
    ('The music was too loud for me.', '2031-01-07 17:00:00', 10, 9),
    ('Incredible performance by the band!', '2031-01-08 19:00:00', 11, 10);


INSERT INTO reports (event_id, user_id, reason, r_status)
VALUES 
    (1, 2, 'Reason for report 1', 'pending'),
    (2, 2, 'Reason for report 2', 'pending'),
    (3, 2, 'Reason for report 3', 'pending'),
    (1, 3, 'The music was too loud.', 'pending'),
    (2, 4, 'Overcrowded venue.', 'pending'),
    (3, 5, 'Unprofessional event staff.', 'reviewed'),
    (4, 6, 'Food was not up to standard.', 'resolved'),
    (5, 7, 'Late start to the event.', 'pending'),
    (6, 8, 'Insufficient parking space.', 'reviewed'),
    (7, 9, 'Technical issues with sound.', 'resolved'),
    (8, 10, 'The event was poorly advertised.', 'pending'),
    (9, 11, 'Inappropriate behavior from attendees.', 'pending'),
    (10, 12, 'Event cancellation without notice.', 'reviewed');
-- Insert valid invitations aligned with the users provided

-- Insert sample data into invites table MISSING