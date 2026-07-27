# Pivot App Backend - REST API Documentation

**Version:** 1.3 (implementation-aligned)  
**Base URL:** `/api/v1`  
**Authentication:** Bearer Token (Laravel Sanctum)

---

## Table of Contents

1. Authentication
2. Profile
3. Hobbies
4. Activities
5. User Hobbies
6. Research
7. Error Responses
8. Notes and Caveats

---

## Response Behavior

The API does not use one single response envelope for all endpoints. Depending on the controller action, responses may be one of the following:

```json
{
    "message": "..."
}
```

```json
{
    "user": {
        "id": 1
    }
}
```

```json
[
    {
        "id": 1
    }
]
```

```json
{
    "data": [
        {
            "id": 1
        }
    ]
}
```

---

## HTTP Status Codes

| Code | Description      |
| ---- | ---------------- |
| 200  | Success          |
| 201  | Created          |
| 400  | Bad Request      |
| 401  | Unauthorized     |
| 403  | Forbidden        |
| 404  | Not Found        |
| 422  | Validation Error |

---

## 1. Authentication

### 1.1 Register

**Endpoint:** `POST /auth/register`  
**Auth Required:** No

**Request Body (JSON):**

```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**Validation:**

- `name`: nullable, string, max 255
- `email`: required, email, unique in `users`
- `password`: required, string, min 6, confirmed
- `password_confirmation`: required, string, min 6

**Response (201):**

```json
{
    "token": "1|abcdef123456...",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "provider": "email",
        "created_at": "2026-01-20T10:30:00.000000Z"
    }
}
```

### 1.2 Login

**Endpoint:** `POST /auth/login`  
**Auth Required:** No

**Request Body (JSON):**

```json
{
    "email": "john@example.com",
    "password": "password123"
}
```

**Validation:**

- `email`: required, email
- `password`: required, string, min 6

**Response (200):**

```json
{
    "token": "1|abcdef123456...",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "provider": "email",
        "created_at": "2026-01-20T10:30:00.000000Z"
    }
}
```

**Common Errors:**

- `404`: `No account found with this email address.`
- `401`: `Invalid credentials.`
- `422`: `Please login using {Provider}`

### 1.3 Logout

**Endpoint:** `POST /auth/logout`  
**Auth Required:** Yes

**Response (200):**

```json
{
    "message": "Logged out successfully"
}
```

### 1.4 Current User

**Endpoint:** `GET /user/current-user`  
**Auth Required:** Yes

**Response (200):**

```json
{
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "provider": "email",
        "created_at": "2026-01-20T10:30:00.000000Z"
    }
}
```

### 1.5 Forgot Password

**Endpoint:** `POST /forgot-password`  
**Auth Required:** Yes (current route setup)

**Request Body (JSON):**

```json
{
    "email": "john@example.com"
}
```

**Validation:**

- `email`: required, email, exists in `users`

**Response (200):**

```json
{
    "message": "Password reset email sent"
}
```

### 1.6 Reset Password (OTP)

**Endpoint:** `POST /reset-password`  
**Auth Required:** Yes (current route setup)

**Request Body (JSON):**

```json
{
    "email": "john@example.com",
    "otp": "123456",
    "password": "new-password123",
    "password_confirmation": "new-password123"
}
```

**Validation:**

- `email`: required, email
- `otp`: required, 6 digits
- `password`: required, min 8, confirmed

**Response (200):**

```json
{
    "message": "Password reset successful"
}
```

**Common Errors:**

- `400`: `Invalid OTP`
- `400`: `OTP expired`

---

## 2. Profile (`apiResource`)

All profile endpoints require auth.

### Endpoints

- `GET /profile`
- `POST /profile`
- `GET /profile/{profile}`
- `PUT /profile/{profile}`
- `DELETE /profile/{profile}`

### Request Validation (`POST`/`PUT`)

```json
{
    "user_id": 1,
    "country_id": 14,
    "gender": "male",
    "age_range": "18-30",
    "set_your_goal": 5,
    "category": ["Nature & Outdoors", "Creative Arts"],
    "onboarding_completed": true
}
```

- `user_id`: required, integer, exists:users,id
- `country_id`: required, integer, exists:countries,id
- `gender`: required, one of `male|female|other`
- `age_range`: required, one of `5-18|18-30|30-45|45+`
- `set_your_goal`: required, integer, 1-168 (hours)
- `category`: sometimes, array of hobby names

```json
{
    "user_id": 1,
    "country_id": 14,
    "gender": "male",
    "age_range": "18-30",
    "set_your_goal": "40",
    "onboarding_done": true
}
```

### Notable Responses

- `POST /profile` returns `201` and an array containing one resource object.
- `PUT /profile/{profile}` returns:

```json
{
    "message": "data updated successfully"
}
```

- `DELETE /profile/{profile}` returns:

```json
{
    "message": "Profile deleted successfully"
}
```

---

## 3. Hobbies (`apiResource`)

All hobby endpoints require auth.

### Endpoints

- `GET /hobbies`
- `POST /hobbies`
- `GET /hobbies/{hobby}`
- `PUT /hobbies/{hobby}`
- `DELETE /hobbies/{hobby}`

### Request Validation (`POST`/`PUT`)

```json
{
    "name": "Reading",
    "icon_url": "https://cdn.example.com/icons/reading.svg"
}
```

- `name`: required, string, max 255, unique in `hobbies` (ignores current id on update)
- `icon_url`: nullable, string, max 255

### Hobby Resource Shape

```json
{
    "id": 1,
    "name": "Reading",
    "icon": "https://cdn.example.com/icons/reading.svg",
    "activities": [],
    "created_at": "2026-01-20T10:30:00.000000Z",
    "updated_at": "2026-01-20T10:30:00.000000Z"
}
```

### Notable Responses

- `POST /hobbies` returns `201` with one resource object.
- `DELETE /hobbies/{hobby}` returns:

```json
{
    "message": "Hobby deleted successfully"
}
```

---

## 4. Activities (`apiResource`)

All activity endpoints require auth.

### Endpoints

- `GET /activities`
- `POST /activities`
- `GET /activities/{activity}`
- `PUT /activities/{activity}`
- `DELETE /activities/{activity}`

### Request Validation (`POST`/`PUT`)

```json
{
    "hobby_id": 1,
    "title": "Read a chapter",
    "description": "Read one chapter from current book",
    "duration_minutes": 30,
    "energy_level": "3",
    "age_suitability": "18+",
    "neurodiversity_friendly": true
}
```

- `hobby_id`: nullable, exists:hobbies,id
- `title`: nullable, string, max 255
- `description`: nullable, string
- `duration_minutes`: nullable, integer, min 1
- `energy_level`: nullable, string, min 1, max 5
- `age_suitability`: nullable, string, min 0, max 120
- `neurodiversity_friendly`: nullable, boolean

### Activity Resource Shape

```json
{
    "id": 1,
    "title": "Read a chapter",
    "duration": 30,
    "energy": "3",
    "age_suitability": "18+",
    "hobby": {
        "id": 1,
        "name": "Reading"
    }
}
```

### Notable Responses

- `POST /activities` returns `201` with one resource object.
- `DELETE /activities/{activity}` returns:

```json
{
    "message": "Activity deleted successfully"
}
```

---

## 5. User Hobbies (`apiResource` on `user/hobbies`)

All user hobby endpoints require auth.

### Endpoints

- `GET /user/hobbies`
- `POST /user/hobbies`
- `GET /user/hobbies/{user_hobby}`
- `PUT /user/hobbies/{user_hobby}`
- `DELETE /user/hobbies/{user_hobby}`

### Store / Update Request Validation

```json
{
    "hobby_ids": [1, 2, 3]
}
```

- `hobby_ids`: required, array, min 1
- `hobby_ids.*`: must exist in `hobbies.id`

### Notable Responses

- `POST /user/hobbies` returns:

```json
{
    "message": "Hobbies saved successfully"
}
```

- `GET /user/hobbies` returns a hobby resource collection.
- Unauthorized access to another user's item may return `403` with `Unauthorized access to this hobby`.
- `DELETE /user/hobbies/{user_hobby}` returns:

```json
{
    "message": "Hobby removed successfully"
}
```

---

## 6. Research (`apiResource`)

All research endpoints require auth.

### Endpoints

- `GET /research`
- `POST /research`
- `GET /research/{research}`
- `PUT /research/{research}`
- `DELETE /research/{research}`

### Create Research (`POST /research`)

Use `multipart/form-data` when uploading a file.

**Fields:**

- `title`: required, string, max 255
- `research_summary`: required, string
- `research_full_text`: required, string
- `files`: nullable file, allowed `pdf,doc,docx`
- `category`: required, string, max 255

### Update Research (`PUT /research/{research}`)

All fields are `sometimes` for update. If a new `files` upload is provided, old stored file is deleted.

### Research Resource Shape

```json
{
    "title": "Screen Time and Sleep",
    "research_summary": "Summary text...",
    "research_full_text": "Full text...",
    "files": "research_files/abc123.pdf",
    "category": "Health",
    "created_at": "2026-02-01T10:30:00.000000Z",
    "updated_at": "2026-02-01T10:30:00.000000Z"
}
```

### Delete Response

```json
{
    "message": "Research deleted successfully"
}
```

---

## 7. Error Responses

### Validation Error (422)

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "email": ["The email has already been taken."]
    }
}
```

### Unauthenticated (401)

```json
{
    "message": "Unauthenticated."
}
```

### Forbidden (403)

```json
{
    "message": "Unauthorized access to this hobby"
}
```

---

## 8. Notes and Caveats

- Password reset currently uses OTP (`otp`) not token.
- `forgot-password` and `reset-password` are currently inside auth middleware, so bearer token is required.
- Some endpoints return plain resources, some return arrays with resource objects, and some return `{ "message": ... }` objects.
- `GET /activities` and `GET /hobbies` return global collections from repositories (not explicitly filtered by authenticated user in current implementation).
- `POST /app-block-log` expects a batched `events` array and stores one row per app inside each event, including `event_type`.

---

**Last Updated:** March 10, 2026
