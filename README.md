# TIKETOK - Event Management System

Event management platform for concerts, theater performances, and cultural gatherings. Users can create events, manage attendees, facilitate discussions, and organize polls.

## Quick Start

```bash
docker run -d --name lbaw2464 -p 8001:80 gitlab.up.pt:5050/lbaw/lbaw2425/lbaw2464
```

Access: http://localhost:8001

## Credentials

| Role  | Email               | Password |
|-------|---------------------|----------|
| Admin | admin@example.com   | 12345678 |
| User  | user1@example.com   | 12345678 |

## Core Features

- **Authentication**: Login/logout, registration, profile management
- **Event Management**: Create, edit, delete, join/leave events
- **Event Discovery**: Browse public events, search, filter by tags
- **Interaction**: Comments, polls, file sharing, attendee lists
- **Administration**: User management, event reports, moderation
- **Invitations**: Send/receive event invitations

## Planning & Development Phases

### A1-A3: Requirements Specification (ER)
- **A1**: Project presentation, goals, motivation, main features
- **A2**: Actors definition (Public User, Authenticated User, Event Organizer, Admin) and 35 user stories
- **A3**: Information architecture with sitemap and wireframes (Home Page, Event Details)

### A4-A6: Database Specification (EBD) 
- **A4**: UML class diagram and conceptual data model
- **A5**: Relational schema with 14 tables, functional dependencies, BCNF validation
- **A6**: Performance indexes, 10 triggers, 25+ transactions for data integrity

### A7-A8: Architecture Specification (EAP)
- **A7**: Web resources specification with OpenAPI documentation, 5 modules (M01-M05)
- **A8**: Vertical prototype with 18 implemented user stories and 33 web resources

### A9-A10: Product & Presentation (PA)
- **A9**: Final product with accessibility/usability validation, HTML/CSS validation
- **A10**: Product presentation and video demo

## Technology Stack

- **Backend**: PHP, PostgreSQL 11+
- **Frontend**: HTML5, CSS3, JavaScript
- **Database**: PostgreSQL with indexes, triggers, transactions
- **Deployment**: Docker

## Database Schema

Key tables: `User`, `Event`, `Venue`, `Comment`, `Poll`, `Ticket`, `Notification`

**Setup files:**
- `database_schema.sql` - Database creation script
- `database_population.sql` - Sample data

## Project Structure

```
├── app/                    # PHP application code
├── database/              # SQL scripts
├── public/                # Web assets (CSS, JS, images)
├── resources/             # Views and templates
├── routes/                # Web routes
└── docker/                # Docker configuration
```

## User Stories Implementation

**High Priority (100% Complete):**
- US01: View Home Page
- US02-04: Browse/Search Events  
- US07-09: Authentication
- US10-13: Event Management
- US17-20: Comments & Interaction

**Medium Priority (100% Complete):**
- US15-16: Polls
- US21-22: Attendee Management
- US25-26: Admin Functions

## Development Team

- Rodrigo Gomes de Araújo (up202205515@up.pt)
- Luna Gomes da Cunha (up202205714@up.pt)  
- Afonso Montenegro Gonçalves Ribeiro da Cruz (up202006020@up.pt)
- Miguel Gomes Fernandes (up202207547@up.pt)

## Repository

GitLab: https://gitlab.up.pt/lbaw/lbaw2425/lbaw2464
