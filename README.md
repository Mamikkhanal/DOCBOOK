<<<<<<< HEAD
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

<h1> Project Documentation </h1>

<h1> 1. Requirements </h1>

An Appointment Management System to book appointments with doctors. (Minimum Viable Product (MVP))<br/>
Registration and Authentication: Users  with diiferent roles (patients, doctors, admin) must be able to register, log in, and authenticate.<br/>
Appointment Booking: Patients can view available time slots for doctors and book appointments.<br/>
Schedule Management: Doctors can manage their availability by creating and updating appointment slots.<br/>
Reviews: Patients can leave reviews on service provided after an appointment is completed.<br/>
Payment System: Patients can make payments for their appointments using e-wallet.<br/>
Appointment Status: Patients can view the status of their appointments.<br/>
Admin Role: Admins can manage users, services, appointments, and reviews.<br/>

<h1> 2. Database Schema </h1>

# Database Schema Documentation

## Users Table
| Column Name        | Data Type  | Constraints                          |
|--------------------|------------|--------------------------------------|
| id                 | BIGINT     | Primary Key                          |
| name               | STRING     | Not Null                             |
| email              | STRING     | Unique, Not Null                     |
| email_verified_at  | TIMESTAMP  | Nullable                             |
| password           | STRING     | Not Null                             |
| phone              | STRING     | Not Null                             |
| role               | ENUM       | `admin`, `patient`, `doctor`         |

---

## Doctors Table
| Column Name    | Data Type  | Constraints                          |
|----------------|------------|--------------------------------------|
| id             | BIGINT     | Primary Key                          |
| user_id        | BIGINT     | Foreign Key (users.id)               |
| specialization | STRING     | Not Null                             |
| is_available   | BOOLEAN    | Default: `true`                      |

---

## Patients Table
| Column Name | Data Type  | Constraints                          |
|-------------|------------|--------------------------------------|
| id          | BIGINT     | Primary Key                          |
| user_id     | BIGINT     | Foreign Key (users.id)               |
| age         | INTEGER    | Not Null                             |

---

## Payments Table
| Column Name    | Data Type  | Constraints                          |
|----------------|------------|--------------------------------------|
| id             | BIGINT     | Primary Key                          |
| user_id        | BIGINT     | Foreign Key (users.id)               |
| appointment_id | BIGINT     | Foreign Key (appointments.id)        |
| service_id     | BIGINT     | Foreign Key (services.id)            |
| pid            | STRING     | Nullable                             |
| amount         | FLOAT      | Not Null                             |
| status         | ENUM       | `paid`, `unpaid` (Default:`paid`)    |

---

## Appointments Table
| Column Name    | Data Type  | Constraints                          |
|----------------|------------|--------------------------------------|
| id             | BIGINT     | Primary Key                          |
| patient_id     | BIGINT     | Foreign Key (patients.id)            |
| doctor_id      | BIGINT     | Foreign Key (doctors.id)             |
| service_id     | BIGINT     | Foreign Key (services.id)            |
| date           | DATE       | Not Null                             |
| start_time     | TIME       | Not Null                             |
| end_time       | TIME       | Not Null                             |
| status         | ENUM       | `pending`, `booked`, `cancelled`,    |     
|                |            | `completed`, `rescheduled` (Default: |     
|                |            | `pending`)                           |
| description    | LONGTEXT   | Not Null                             |

---

## Services Table
| Column Name   | Data Type  | Constraints                          |
|---------------|------------|--------------------------------------|
| id            | BIGINT     | Primary Key                          |
| name          | STRING     | Not Null                             |
| description   | STRING     | Not Null                             |
| category      | ENUM       | Default: ''                          |
| price         | FLOAT      | Not Null                             |
| is_available  | BOOLEAN    | Default: `true`                      |

---

## Reviews Table
| Column Name    | Data Type  | Constraints                          |
|----------------|------------|--------------------------------------|
| id             | BIGINT     | Primary Key                          |
| appointment_id | BIGINT     | Foreign Key (appointments.id)        |
| review         | LONGTEXT   | Not Null                             |

---

## Schedules Table
| Column Name    | Data Type  | Constraints                          |
|----------------|------------|--------------------------------------|
| id             | BIGINT     | Primary Key                          |
| doctor_id      | BIGINT     | Foreign Key (doctors.id)             |
| date           | DATE       | Not Null                             |
| start_time     | TIME       | Not Null                             |
| end_time       | TIME       | Not Null                             |

---

## Slots Table
| Column Name    | Data Type  | Constraints                          |
|----------------|------------|--------------------------------------|
| id             | BIGINT     | Primary Key                          |
| schedule_id    | BIGINT     | Foreign Key (schedules.id)           |
| appointment_id | BIGINT     | Foreign Key (appointments.id)        |
| date           | DATE       | Not Null                             |
| start_time     | TIME       | Not Null                             |
| end_time       | TIME       | Not Null                             |
| is_booked      | BOOLEAN    | (Default: `false`)                   |

---

## Specializations Table
| Column Name | Data Type  | Constraints                          |
|-------------|------------|--------------------------------------|
| id          | BIGINT     | Primary Key                          |
| name        | STRING     | Not Null                             |



<h1> 3. Features </h1>

### Streamlined Appointment Booking  
Patients can easily find available doctors and schedule appointments, while doctors efficiently manage their availability.

### Enhanced Patient Feedback  
A structured review system allows patients to share their experiences, helping others make informed choices.

### Simplified Payment Processing  
An automated and digital payment system ensures secure transactions and clear payment tracking for both patients and doctors.

### Comprehensive Medical Records  
The system consolidates appointments, reviews, and payments to create a detailed medical history for each patient.

### Secure Role-Based Access  
Customized access ensures patients, doctors, and administrators interact only with data relevant to their roles.

### Summary  
The platform improves appointment management, feedback collection, and payment processing, enhancing the overall healthcare experience.
