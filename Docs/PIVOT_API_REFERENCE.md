# Pivot App Backend - API Quick Reference

**Base URL:** `http://your-domain/api/v1`  
**Authentication:** Bearer Token (Laravel Sanctum)

---

## Authentication Endpoints

| Method | Endpoint             | Auth | Notes                                            |
| ------ | -------------------- | ---- | ------------------------------------------------ |
| POST   | `/auth/register`     | No   | Requires `password_confirmation`; password min 6 |
| POST   | `/auth/login`        | No   | Password min 6                                   |
| POST   | `/auth/logout`       | Yes  | Revokes current user's tokens                    |
| GET    | `/user/current-user` | Yes  | Returns `{ "user": { ... } }`                    |
| POST   | `/forgot-password`   | Yes  | Sends OTP email                                  |
| POST   | `/reset-password`    | Yes  | Uses `otp`, not token                            |

---

## Profile Endpoints (`apiResource`)

| Method | Endpoint             | Auth | Description    |
| ------ | -------------------- | ---- | -------------- |
| GET    | `/profile`           | Yes  | List profiles  |
| POST   | `/profile`           | Yes  | Create profile |
| GET    | `/profile/{profile}` | Yes  | Get profile    |
| PUT    | `/profile/{profile}` | Yes  | Update profile |
| DELETE | `/profile/{profile}` | Yes  | Delete profile |

**Profile fields:** `user_id`, `country_id`, `gender`, `age_range`, `set_your_goal`, `onboarding_completed`  
**Profile response key:** `onboarding_done`

---

## Hobby Endpoints (`apiResource`)

| Method | Endpoint           | Auth | Description  |
| ------ | ------------------ | ---- | ------------ |
| GET    | `/hobbies`         | Yes  | List hobbies |
| POST   | `/hobbies`         | Yes  | Create hobby |
| GET    | `/hobbies/{hobby}` | Yes  | Get hobby    |
| PUT    | `/hobbies/{hobby}` | Yes  | Update hobby |
| DELETE | `/hobbies/{hobby}` | Yes  | Delete hobby |

**Hobby fields:** `name`, `icon_url`  
**Hobby response key:** `icon`

---

## Activity Endpoints (`apiResource`)

| Method | Endpoint                 | Auth | Description     |
| ------ | ------------------------ | ---- | --------------- |
| GET    | `/activities`            | Yes  | List activities |
| POST   | `/activities`            | Yes  | Create activity |
| GET    | `/activities/{activity}` | Yes  | Get activity    |
| PUT    | `/activities/{activity}` | Yes  | Update activity |
| DELETE | `/activities/{activity}` | Yes  | Delete activity |

**Activity request fields:** `hobby_id`, `title`, `description`, `duration_minutes`, `energy_level`, `age_suitability`, `neurodiversity_friendly`  
**Activity response keys:** `duration`, `energy`, nested `hobby`

---

## User Hobby Endpoints (`apiResource`)

| Method | Endpoint                     | Auth | Description                                    |
| ------ | ---------------------------- | ---- | ---------------------------------------------- |
| GET    | `/user/hobbies`              | Yes  | List user hobby relations (as hobby resources) |
| POST   | `/user/hobbies`              | Yes  | Sync hobbies to user                           |
| GET    | `/user/hobbies/{user_hobby}` | Yes  | Get one linked hobby                           |
| PUT    | `/user/hobbies/{user_hobby}` | Yes  | Update linked hobby mapping                    |
| DELETE | `/user/hobbies/{user_hobby}` | Yes  | Remove linked hobby                            |

**User hobby payload:**

```json
{
    "hobby_ids": [1, 2, 3]
}
```

---

## Research Endpoints (`apiResource`)

| Method | Endpoint               | Auth | Description           |
| ------ | ---------------------- | ---- | --------------------- |
| GET    | `/research`            | Yes  | List research entries |
| POST   | `/research`            | Yes  | Create research       |
| GET    | `/research/{research}` | Yes  | Get research entry    |
| PUT    | `/research/{research}` | Yes  | Update research entry |
| DELETE | `/research/{research}` | Yes  | Delete research entry |

**Create fields:** `title`, `research_summary`, `research_full_text`, `category`, optional `files` (`pdf|doc|docx`)  
**Content type with file:** `multipart/form-data`

---

## Common Request Headers

```http
Authorization: Bearer YOUR_TOKEN_HERE
Accept: application/json
Content-Type: application/json
```

For research file upload, use `multipart/form-data`.

---

## Minimal Payload Examples

### Register

```json
{
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

### Reset Password

```json
{
    "email": "john@example.com",
    "otp": "123456",
    "password": "newpassword123",
    "password_confirmation": "newpassword123"
}
```

### Create Profile

```json
{
    "user_id": 1,
    "country_id": 14,
    "gender": "male",
    "age_range": "18-30",
    "set_your_goal": "40",
    "category": "Nature & Outdoors"
}
```

---

## Common Errors

| Code | Meaning                               |
| ---- | ------------------------------------- |
| 400  | Invalid OTP / OTP expired             |
| 401  | Unauthenticated / invalid credentials |
| 403  | Unauthorized resource access          |
| 404  | Resource not found                    |
| 422  | Validation error                      |

---

## Implementation Notes

- `forgot-password` and `reset-password` are currently protected by auth middleware.
- Response wrapping is not consistent across all endpoints (resource objects, collections, and message payloads are mixed).
- Activities and hobbies list endpoints are currently repository-wide and not explicitly filtered by authenticated user.

---

**Last Updated:** March 10, 2026  
**API Version:** 1.3 (implementation-aligned)
