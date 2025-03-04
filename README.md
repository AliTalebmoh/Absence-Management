# Absence Management System

A web-based application for managing student absences in an educational institution. This system allows administrators to track and record student absences efficiently.

## Features

- **Class Management**: Create and manage different classes
- **Student Management**: Add and manage students within classes
- **Subject Management**: Define subjects for absence tracking
- **Absence Recording**:
  - Record absences by class and student
  - Specify morning, afternoon, or full-day absences
  - Automatic hours calculation based on period:
    - Morning: 4 hours
    - Afternoon: 4 hours
    - Full Day: 8 hours
  - Manual hour adjustment available if needed

## Installation

1. Clone the repository:
```bash
git clone [repository-url]
cd absence-management
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install JavaScript dependencies:
```bash
npm install
```

4. Copy the environment file:
```bash
cp .env.example .env
```

5. Generate application key:
```bash
php artisan key:generate
```

6. Configure your database in `.env` file:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=absence_management
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

7. Run migrations and seed the database:
```bash
php artisan migrate --seed
```

8. Build assets:
```bash
npm run build
```

## Usage Guide

### Recording an Absence

1. Navigate to the Absences section
2. Click "Record New Absence"
3. Select the class containing the student
4. From the class view, find the student and click "Record Absence"
5. Fill in the absence details:
   - Select the subject
   - Choose the date
   - Select the period (Morning/Afternoon/Full Day)
   - The hours will be automatically calculated but can be adjusted if needed
6. Click "Record Absence" to save

### Editing an Absence

1. Go to the Absences list
2. Find the absence record you want to modify
3. Click the "Edit" button
4. Update the necessary information
5. Click "Update Absence" to save changes

### Viewing Absences

- The main Absences page shows all recorded absences
- Each record displays:
  - Date
  - Student Name
  - Class
  - Subject
  - Period (Morning/Afternoon/Full Day)
  - Hours Absent
  - Actions (Edit/Delete)

### Managing Students

1. Go to the Students section
2. You can:
   - Add new students
   - Assign students to classes
   - View student details
   - Edit student information
   - View a student's absence history

### Managing Classes

1. Navigate to the Classes section
2. You can:
   - Create new classes
   - View class lists
   - Edit class details
   - View class absence statistics

## Default Login

After installation, you can log in with these default credentials:
- Email: admin@admin.com
- Password: admin

## Security

Remember to:
- Change the default admin password after first login
- Regularly backup your database
- Keep the system updated with the latest security patches

## Support

For issues or questions, please:
1. Check the existing issues in the repository
2. Create a new issue if your problem isn't already reported
3. Provide detailed information about your problem

## License

This project is licensed under the MIT License.
