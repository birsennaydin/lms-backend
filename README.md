# LMS Backend – Domain Model & API Design Specification

This document provides a full overview of the LMS backend domain model, application services, repository structure, and the designed HTTP API that could be built on top of this architecture.  
The exercise does **not require implementing a real API**, only designing one.  
All domain logic, entities, and services are fully implemented and tested.

---

# 1. Overview

This backend is built following **Domain‑Driven Design**, **Clean Architecture**, and **Test‑Driven Development** principles.

The project consists of:

- **Domain layer**: Entities, Value Objects, Domain Services
- **Application layer**: Orchestrates use‑cases through services
- **Repository layer**: Interfaces + In‑Memory implementations
- **Tests**: Fully covers both domain and application logic
- **API Design**: A clear HTTP interface mapping to application services

No frameworks and no database are used (as required by the exercise).

---

# 2. Folder Structure

```
src/
  Application/
    AccessService.php
    CourseService.php
    EnrolmentService.php

  Repository/
    CourseRepository.php
    EnrolmentRepository.php
    StudentRepository.php
    InMemory/
      InMemoryCourseRepository.php
      InMemoryEnrolmentRepository.php
      InMemoryStudentRepository.php

  Value/
    ContentId.php
    CourseId.php
    DateRange.php
    EnrolmentId.php
    StudentId.php

  ContentItem.php
  Course.php
  Enrolment.php
  Lesson.php
  Homework.php
  PrepMaterial.php
  Student.php
  AccessChecker.php

tests/
  Application/
    AccessServiceTest.php
    CourseServiceTest.php
    EnrolmentServiceTest.php

  AccessCheckerTest.php
  CourseTest.php
```

---

# 3. Domain Model Summary

### **Entities**
- `Course`
- `Lesson`
- `Homework`
- `PrepMaterial`
- `ContentItem` (parent class for all content)
- `Student`
- `Enrolment`

### **Value Objects**
- `ContentId`
- `CourseId`
- `StudentId`
- `EnrolmentId`
- `DateRange`

### **Domain Service**
- `AccessChecker`

### **Application Services**
- `CourseService`
- `EnrolmentService`
- `AccessService`

These services provide all business‑level operations that a future API would call.

---

# 4. API Design (Specification Only)

Base URL:

```
/api/v1
```

---

# 5. Students API

## **POST /api/v1/students**

Creates a new student.

### Response
```json
{ "id": "student-uuid" }
```

Maps to:
- `StudentRepository::save()`

---

# 6. Courses API

## **POST /api/v1/courses**

Creates a new course.

### Request
```json
{
  "start": "2025-05-13",
  "end": "2025-06-13"
}
```

### Response
```json
{
  "id": "course-uuid",
  "start": "2025-05-13",
  "end": "2025-06-13"
}
```

Maps to:
- `CourseService::createCourse()`

---

# 7. Add Content to Course

## **POST /api/v1/courses/{courseId}/lessons**

### Request
```json
{
  "schedule": "2025-05-15 10:00"
}
```

### Response
```json
{
  "id": "uuid",
  "type": "lesson",
  "schedule": "2025-05-15 10:00"
}
```

## **POST /api/v1/courses/{courseId}/prep-material**

### Request
```json
{ "title": "Reading Guide" }
```

## **POST /api/v1/courses/{courseId}/homework**

### Request
```json
{ "title": "Label a Plant Cell" }
```

Maps to:
- `CourseService::addContentToCourse()`

---

# 8. Enrolment API

## **POST /api/v1/courses/{courseId}/enrolments**

Enrolls a student into a course.

### Request
```json
{
  "studentId": "uuid",
  "start": "2025-05-10",
  "end": "2025-06-12"
}
```

### Response
```json
{ "id": "enrolment-uuid" }
```

Maps to:
- `EnrolmentService::enrolStudentInCourse()`

---

# 9. Content Access API

The central use case of this system.

## **GET /api/v1/courses/{courseId}/content/{contentId}/access**
Query parameters:

```
?studentId={id}
&at=2025-05-15T10:00:00
```

### Response (Access Allowed)
```json
{ "canAccess": true }
```

### Response (Access Denied)
```json
{
  "canAccess": false,
  "reason": "lesson_not_started"
}
```

Maps to:
- `AccessService::canStudentAccessContent()`
- internally uses domain service `AccessChecker`

---

# 10. Course Contents Listing

## **GET /api/v1/courses/{courseId}/contents**

### Response
```json
[
  {
    "id": "uuid1",
    "type": "lesson",
    "schedule": "2025-05-15 10:00"
  },
  {
    "id": "uuid2",
    "type": "prep_material",
    "title": "Reading Guide"
  }
]
```

Maps to:
- `Course::getContents()`

---

# 11. Student Access Report

## **GET /api/v1/students/{studentId}/courses/{courseId}/access**

### Response
```json
[
  {
    "contentId": "uuid1",
    "type": "lesson",
    "at": "2025-05-15T10:00:00",
    "canAccess": true
  }
]
```

---

# 12. API to Application Service Mapping

| API Route | Application Service | Domain Model |
|----------|----------------------|--------------|
| POST /students | — | Student |
| POST /courses | CourseService | Course |
| POST /courses/{id}/lessons | CourseService | Lesson |
| POST /courses/{id}/enrolments | EnrolmentService | Enrolment |
| GET /courses/{id}/content/{cid}/access | AccessService | AccessChecker |
| GET /courses/{id}/contents | CourseService | ContentItem[] |

This shows that *controllers could be added easily* if needed.


# 13. How to Run Tests

```
composer install
vendor/bin/phpunit --testdox
```

---
