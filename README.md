# ICU Virtual Visit System

Laravel 11 MVC application for coordinating virtual ICU visits between families and clinical staff. The app includes role-based dashboards, patient assignment, family visit requests, approval workflows, embedded video rooms, medical report uploads, database notifications, and MongoDB-backed activity/call logging.

## Tech stack

| Layer | Technology |
| --- | --- |
| Framework | Laravel 11, PHP 8.2+ |
| Authentication | Laravel Breeze session auth |
| Frontend | Blade, Tailwind CSS, Alpine.js, Vite |
| Primary database | SQLite by default, configurable through Laravel `.env` |
| Background jobs / cache / sessions | Database-backed Laravel drivers by default |
| Realtime config | Laravel Reverb + Echo configuration is present |
| Video rooms | Embedded Jitsi Meet room per approved appointment |
| Audit logs | MongoDB through `mongodb/laravel-mongodb` |

## Current features

- Role-aware portals for `admin`, `doctor`, `nurse`, and `family` users.
- Admin user management, analytics, and MongoDB activity log views.
- Patient records with assigned doctor, assigned nurse, primary family contact, ICU ward, bed number, status, and emergency contact.
- Family users can request visits only for patients linked to their account.
- Doctors and admins can approve or reject visit requests; approval generates a UUID room id and sends email/database notifications.
- Authorized users can join approved video rooms: linked family user, assigned doctor, or assigned nurse. Admins manage visits but do not join rooms from the UI.
- Clinical staff can upload medical reports. Families can download reports when linked to the patient or when they have visit history for that patient.
- MongoDB activity and call logs are best-effort: logging failures are caught so the main application flow keeps running.
- Authenticated JSON endpoints expose scoped patients, appointments, and unread notifications.

## Project structure

```text
app/
  Console/Commands/MongoPingCommand.php       MongoDB connectivity check
  Http/Controllers/                           Web, API, auth, profile, and admin controllers
  Http/Middleware/RoleMiddleware.php          Role gate used by route groups
  Mail/                                       Appointment approval/rejection emails
  Models/                                     SQL models plus MongoDB ActivityLog and CallLog
  Notifications/                              Appointment status notification
  Services/LoggingService.php                 Best-effort MongoDB logging facade
  Support/VideoRoomGate.php                   Video room authorization helper
  View/Components/                            Layout components

bootstrap/                                    Laravel bootstrap files
config/                                       App, auth, DB, Reverb, queue, mail, logging config
database/
  database.sqlite                             Local SQLite database
  factories/UserFactory.php
  migrations/                                 Users, cache, jobs, patients, appointments, reports, notifications
  seeders/DatabaseSeeder.php                  Demo users, patients, and visits

public/
  images/icu-hero.png                         Static image asset
  index.php                                   Laravel front controller

resources/
  css/app.css                                 Tailwind entry and app styles
  js/app.js, bootstrap.js, echo.js            Vite entry, Axios, Echo/Reverb setup
  views/                                      Blade pages, layouts, components, emails, dashboards

routes/
  web.php                                     Main authenticated web app routes
  auth.php                                    Breeze auth routes
  api.php                                     Authenticated JSON endpoints
  channels.php                                Broadcast channel authorization
  console.php                                 Console route file

composer.json                                 PHP dependencies and scripts
package.json                                  Vite/Tailwind frontend scripts
phpunit.xml                                   PHPUnit config; `tests/Feature` is configured when tests are added
vite.config.js, tailwind.config.js            Frontend build configuration
```

## Requirements

- PHP 8.2+ with common Laravel extensions enabled.
- Composer.
- Node.js 18+ and npm.
- SQLite for the default local setup, or another Laravel-supported SQL database configured in `.env`.
- PHP `ext-mongodb` and a running MongoDB server if you want activity and call logs.

## Installation

```bash
git clone <your-repo-url> virtual-icu-visit
cd virtual-icu-visit
composer install
npm install
cp .env.example .env
php artisan key:generate
```

For the default SQLite setup, make sure the database file exists:

```bash
touch database/database.sqlite
```

On Windows PowerShell:

```powershell
New-Item -ItemType File -Force database/database.sqlite
```

Then migrate and seed:

```bash
php artisan migrate --seed
php artisan storage:link
npm run build
```

## Environment notes

Recommended local values from `.env.example`:

```dotenv
DB_CONNECTION=sqlite
SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database
BROADCAST_CONNECTION=reverb
MAIL_MAILER=log
```

Optional MongoDB logging values:

```dotenv
DB_HOST_MONGO=127.0.0.1
DB_PORT_MONGO=27017
DB_DATABASE_MONGO=icu_visit
DB_USERNAME_MONGO=
DB_PASSWORD_MONGO=
DB_AUTHENTICATION_DATABASE_MONGO=admin
```

Set `APP_NAME="ICU Virtual Visit System"` if you want the browser title and mail sender name to match the project.

## Running locally

Run the app and frontend in separate terminals:

```bash
php artisan serve
npm run dev
```

Or use the Composer development script, which starts Laravel, Reverb, the queue listener, Laravel Pail logs, and Vite together:

```bash
composer run dev
```

If MongoDB is configured, check connectivity with:

```bash
php artisan mongo:ping
```

## Video visits

Approved appointments receive a `room_id` UUID. The video page validates the current user through `VideoRoomGate` before rendering `resources/views/video/call.blade.php`.

The current call UI embeds Jitsi Meet with a room name based on the approved appointment:

```text
ICU-Visit-{room_id}
```

Because the app loads `https://meet.jit.si/external_api.js`, video calls need browser access to that external script. The app-level authorization still happens before the meeting is embedded.

## Main routes

| Area | Routes |
| --- | --- |
| Landing | `GET /` |
| Dashboard | `GET /dashboard` |
| Profile | `GET/PATCH/DELETE /profile` |
| Patients | `GET /patients`, `GET /patients/{patient}`, plus create/edit/update/delete for authorized roles |
| Appointments | `GET /appointments`, family create/store, admin/doctor approve/reject, cancel/delete |
| Reports | `POST /reports`, `GET /reports/{report}/download`, `DELETE /reports/{report}` |
| Video | `GET /video-call/{room_id}` |
| Notifications | `GET /notifications`, mark one/all read, delete |
| Admin | `admin/users`, `admin/activity`, `admin/analytics` |

## JSON API

All API routes require an authenticated session:

- `GET /api/patients` - medical staff only; scoped for doctors and nurses.
- `GET /api/appointments` - scoped by role.
- `GET /api/notifications` - unread notifications for the current user.

## Demo flow

1. Start the app with `composer run dev` or with `php artisan serve` and `npm run dev`.
2. Sign in as `admin@example.com` and review users, analytics, patients, and activity logs.
3. Sign in as `doctor1@example.com`, open visit requests, and approve a pending request for an assigned patient.
4. Sign in as `family1@example.com`, open the approved visit, and use `Join meeting`.
5. Sign in as the assigned doctor or nurse in another browser/session and join the same approved room.
6. Upload a report as clinical staff from a patient profile, then download it as an authorized family user.

## Verification

Useful checks for this checkout:

```bash
php artisan route:list
npm run build
php artisan mongo:ping
```

`phpunit.xml` is configured for `tests/Feature`, but this checkout does not currently include a `tests` directory. When tests are added, run:

```bash
composer test
```

## MongoDB notes

- Collections are created on first write: `activity_logs` and `call_logs`.
- If MongoDB is unavailable, `LoggingService` writes a warning to Laravel logs and the web request continues.
- The admin activity screen reports connection problems instead of failing the whole dashboard.
- If Composer reports a missing MongoDB extension, install/enable `ext-mongodb` for your PHP runtime.

## Acknowledgements

Built with Laravel, Laravel Breeze, Tailwind CSS, Laravel Reverb, MongoDB, and Jitsi Meet.
