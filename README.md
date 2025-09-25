# Notes App

A modern, feature-rich Laravel application for creating, organizing, and sharing notes. This application allows users to manage their personal notes, organize them into groups, and selectively publish them via unique URLs for public access.

## Features

- **User Authentication**: Secure login and registration system powered by Laravel Breeze
- **Note Management**: Create, edit, delete, and organize your notes
- **Groups**: Organize notes into custom groups for better management
- **Pinning**: Pin important notes to the top of your list
- **Publishing**: Publish individual notes or entire groups via unique URLs
- **Image Support**: Upload and attach images to your notes
- **API Access**: Full-featured API for programmatic access to notes
- **Public Access**: Share your published notes with anyone via unique URLs

## Technology Stack

- **Backend**: Laravel 12.x
- **Frontend**: Blade templates, TailwindCSS
- **Database**: SQLite (default), MySQL, PostgreSQL (supported)
- **Authentication**: Laravel Breeze
- **Architecture**: Repository pattern, Action classes, DTOs

## Requirements

- PHP 8.2+
- Composer 2.0+
- Node.js 18+ & NPM 9+
- SQLite or any other database supported by Laravel

## Installation

### Step 1: Clone the Repository

```bash
git clone https://github.com/GH0ST-000/NoteApp
cd notes-app
```

### Step 2: Install Dependencies

Install PHP dependencies:
```bash
composer install
```

Install and compile frontend assets:
```bash
npm install
npm run dev
```

### Step 3: Configure Environment

Create a copy of the environment file:
```bash
cp .env.example .env
```

Generate application key:
```bash
php artisan key:generate
```

### Step 4: Set Up Database

For SQLite (default):
```bash
# Configure the database in the .env file
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

# Create the SQLite database file
touch database/database.sqlite
```

For MySQL or PostgreSQL, update your `.env` file with the appropriate credentials.

### Step 5: Run Migrations and Seed Database

```bash
php artisan migrate --seed
```

### Step 6: Set Up File Storage

Create a symbolic link for public file storage:
```bash
php artisan storage:link
```

### Step 7: Start the Development Server

```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

## Demo User

After seeding the database, you can log in with the following credentials:
- **Email**: test@example.com
- **Password**: password

## Usage Guide

### Managing Notes

1. **Creating a Note**:
   - Navigate to `/notes/create` or click the "New Note" button
   - Fill in the title and content
   - Optionally:
     - Select a group
     - Toggle "Pin" to pin the note to the top
     - Toggle "Publish" to make the note publicly accessible
     - Upload an image

2. **Organizing Notes**:
   - Create groups at `/groups/create`
   - Assign notes to groups during creation or editing
   - View all notes in a group by clicking on the group name

3. **Publishing Notes**:
   - Toggle the "Publish" option when creating or editing a note
   - A unique URL will be generated automatically
   - Share the URL with others to allow them to view the note

### API Usage

#### Authentication

The API uses Laravel Sanctum for authentication. To use protected endpoints:

1. Log in through the web interface
2. Use the same session cookie for API requests

#### API Endpoints

##### Protected Endpoints (require authentication)

- `POST /api/notes` - Create a new note
  - Required fields: `title`, `content`
  - Optional fields: `group_id`, `is_pinned`, `is_published`, `image`

- `GET /api/notes` - Get all notes for the authenticated user
  - Optional query parameter: `?is_pinned=true` to filter pinned notes

##### Public Endpoints

- `GET /api/published/{slug}` - Get a published note or group by slug
  - Returns note or group data based on the slug
  - Returns 404 if the content is not published or doesn't exist

## Web Routes

### Protected Routes (require authentication)

- `/notes` - List all notes
- `/notes/create` - Create a new note
- `/notes/{note}` - View a note
- `/notes/{note}/edit` - Edit a note
- `/groups` - List all groups
- `/groups/create` - Create a new group
- `/groups/{group}` - View a group and its notes
- `/groups/{group}/edit` - Edit a group

### Public Routes

- `/p/{slug}` - View a published note
- `/g/{slug}` - View a published group and its notes

## Development

### Running Tests

The application includes comprehensive tests for all features:

```bash
# Run all tests
php artisan test

# Run only unit tests
php artisan test --filter=Unit

# Run only feature tests
php artisan test --filter=Feature
```

### Project Structure

The application follows a clean architecture with:

- **Controllers**: Handle HTTP requests and responses
- **Actions**: Contain business logic
- **DTOs**: Transfer data between layers
- **Repositories**: Handle data access
- **Models**: Represent database entities
- **Views**: Blade templates for the UI

### Troubleshooting

#### Common Issues

1. **Storage Permissions**:
   If image uploads aren't working, check that the `storage/app/public` directory is writable and that you've run `php artisan storage:link`.

2. **Database Connection**:
   If you're having database issues, verify your `.env` configuration and ensure the database file exists (for SQLite).

3. **Frontend Assets**:
   If the UI looks unstyled, make sure you've run `npm install` and `npm run dev`.
# NoteApp
