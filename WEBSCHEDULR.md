# WEBSCHEDULR.md

```markdown
# WebSchedulr Documentation

## Overview
WebSchedulr is a comprehensive appointment scheduling system built with Laravel and Vue.js. This document provides detailed information about the application architecture, key components, and development guidelines.

## Project Structure

### Backend (Laravel)
- **Controllers**: Handle HTTP requests and return responses
- **Models**: Represent database tables and relationships
- **Migrations**: Define database schema
- **Routes**: Define API endpoints and web routes
- **Services**: Contain business logic separated from controllers
- **Validators**: Handle form validation

### Frontend (Vue.js + Inertia)
- **Pages**: Vue components that represent full pages
- **Components**: Reusable Vue components
- **Layouts**: Page templates that wrap content
- **Stores**: State management (if using Pinia/Vuex)
- **Utils**: Helper functions and utilities

## Key Features Documentation

### Appointment System
Appointments are the core entity of WebSchedulr and include:
- Client information
- Service type
- Date and time
- Duration
- Status (pending, confirmed, completed, cancelled)
- Staff assignment
- Notes and additional information

### Calendar Implementation
The calendar system provides three main views:
- **Day View**: Detailed view of a single day's appointments
- **Week View**: Overview of appointments across a week
- **Month View**: Monthly planning view with appointment indicators

### Dashboard Analytics
The dashboard uses Chart.js to visualize:
- Appointments by status
- Revenue trends
- Service popularity
- Client retention

## Development Guidelines

### Coding Standards
- Follow PSR-12 for PHP code
- Use Vue.js Style Guide for frontend code
- Use TypeScript for type safety where possible
- Write descriptive commit messages

### Testing Strategy
- **Unit Tests**: Test individual components and functions
- **Feature Tests**: Test complete features and workflows
- **Browser Tests**: Test UI interactions with Laravel Dusk

### Git Workflow
- Main branch: production-ready code
- Develop branch: integration branch
- Feature branches: for new features
- Hotfix branches: for urgent fixes

### CI/CD Pipeline
GitHub Actions handles:
- Code linting and formatting checks
- Running automated tests
- Building frontend assets
- Deployment to staging/production environments

## Configuration Options

### Environment Variables
Key configuration options are set via environment variables:
- `APP_TIMEZONE`: Default timezone for appointments
- `CALENDAR_START_HOUR`: Default start hour for day view
- `CALENDAR_END_HOUR`: Default end hour for day view
- `APPOINTMENT_BUFFER`: Buffer time between appointments

### User Preferences
Users can customize their experience with:
- Dark/light mode toggle
- Calendar view preferences
- Notification settings
- Working hours configuration

## API Documentation
WebSchedulr provides a RESTful API for:
- Appointment management
- Client management
- Service management
- Calendar operations
- User authentication

## Roadmap
Planned features for future releases:
- Client portal for self-scheduling
- SMS notifications
- Payment processing integration
- Resource scheduling
- Multi-location support
```

This Markdown file provides more detailed documentation about WebSchedulr, complementing the information in your README.md. You can save this as `WEBSCHEDULR.md` in your project root.