# Pivot App Backend - API Quick Reference

**Base URL:** `http://your-domain/api/v1`  
**Authentication:** Bearer Token (Laravel Sanctum)

---

## Table of Contents

1. [Authentication Endpoints](#authentication-endpoints)
2. [User Profile Endpoints](#user-profile-endpoints)
3. [Hobby Endpoints](#hobby-endpoints)
4. [User Hobby Endpoints](#user-hobby-endpoints)
5. [Activity Endpoints](#activity-endpoints)
6. [Common Request Headers](#common-request-headers)
7. [HTTP Status Codes](#http-status-codes)
8. [Example Usage](#example-usage)

---

## Authentication Endpoints

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| POST | `/auth/register` | ❌ | Create new account |
| POST | `/auth/login` | ❌ | Login user |
| POST | `/auth/logout` | ✅ | Logout user |
| GET | `/user/current-user` | ✅ | Get authenticated user |

---

## User Profile Endpoints

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| POST | `/user/profile` | ✅ | Create or update user profile |

---

## Hobby Endpoints

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/hobbies` | ✅ | List all hobbies |
| GET | `/hobbies/{hobby}` | ✅ | Get hobby details |
| POST | `/hobbies` | ✅ | Create hobby |
| PUT | `/hobbies/{hobby}` | ✅ | Update hobby |
| DELETE | `/hobbies/{hobby}` | ✅ | Delete hobby |

---

## User Hobby Endpoints

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| POST | `/user/hobbies` | ✅ | Add hobby to user |

---

## Activity Endpoints

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/activities` | ✅ | List user activities |
| POST | `/activities` | ✅ | Create activity |
| PUT | `/activities/{activity}` | ✅ | Update activity |
| DELETE | `/activities/{activity}` | ✅ | Delete activity |

---

## Common Request Headers

### Authentication

#### Register
**POST** `/auth/register`

```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123"
}
```

**Response:**
```json
{
  "token": "1|abc123...",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "provider": "email",
    "created_at": "2026-01-20T10:30:00.000000Z"
  }
}
```

---

#### Login
**POST** `/auth/login`

```json
{
  "email": "john@example.com",
  "password": "password123"
}
```

**Response:**
```json
{
  "token": "1|abc123...",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "provider": "email",
    "created_at": "2026-01-20T10:30:00.000000Z"
  }
}
```

---

#### Logout
**POST** `/auth/logout`

**Headers:**
```
Authorization: Bearer YOUR_TOKEN
```

**Response:**
```json
{
  "message": "Logged out successfully"
}
```

---

#### Get Current User
**GET** `/user/current-user`

**Headers:**
```
Authorization: Bearer YOUR_TOKEN
```

**Response:**
```json
{
  "id": 1,
  "name": "John Doe",
  "email": "john@example.com",
  "provider": "email",
  "created_at": "2026-01-20T10:30:00.000000Z"
}
```

---

### User Profile

#### Create/Update Profile
**POST** `/user/profile`

**Headers:**
```
Authorization: Bearer YOUR_TOKEN
```

**Request Body:**
```json
{
  "country": "United States",
  "gender": "male",
  "age_range": "18-30",
  "screen_goal_hours": 40,
  "onboarding_completed": true
}
```

**Response:**
```json
{
  "message": "Profile saved successfully",
  "profile": {
    "country": "United States",
    "gender": "male",
    "age_range": "18-30",
    "screen_goal_hours": 40,
    "onboarding_done": true
  }
}
```

---

## Validation Rules

### Register
| Field | Type | Required | Validation |
|-------|------|----------|------------|
| `name` | string | No | max: 255 |
| `email` | string | Yes | email, unique |
| `password` | string | Yes | min: 8 |

### Login
| Field | Type | Required | Validation |
|-------|------|----------|------------|
| `email` | string | Yes | email |
| `password` | string | Yes | - |

### Profile
| Field | Type | Required | Validation |
|-------|------|----------|------------|
| `country` | string | Yes | max: 100 |
| `gender` | string | Yes | enum: male, female, other |
| `age_range` | string | Yes | enum: 5-18, 18-30, 30-45, 45+ |
| `screen_goal_hours` | integer | Yes | min: 1, max: 168 |
| `onboarding_completed` | boolean | No | true/false |

---

## Common Request Headers

```http
Authorization: Bearer YOUR_TOKEN_HERE
Content-Type: application/json
Accept: application/json
```

---

## HTTP Status Codes

| Code | Description |
|------|-------------|
| 200 | Success |
| 201 | Created |
| 400 | Bad Request |
| 401 | Unauthorized |
| 404 | Not Found |
| 422 | Validation Error |
| 500 | Server Error |

---

## Example Usage

### Complete Authentication Flow

```bash
# 1. Register
curl -X POST http://localhost:8000/api/v1/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123"
  }'

# Response: { "token": "1|abc123...", "user": {...} }

# 2. Login (if already registered)
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "password123"
  }'

# 3. Create Profile
curl -X POST http://localhost:8000/api/v1/user/profile \
  -H "Authorization: Bearer 1|abc123..." \
  -H "Content-Type: application/json" \
  -d '{
    "country": "United States",
    "gender": "male",
    "age_range": "18-30",
    "screen_goal_hours": 40,
    "onboarding_completed": true
  }'

# 4. Get Current User
curl -X GET http://localhost:8000/api/v1/user/current-user \
  -H "Authorization: Bearer 1|abc123..."

# 5. List Hobbies
curl -X GET http://localhost:8000/api/v1/hobbies \
  -H "Authorization: Bearer 1|abc123..."

# 6. Create Hobby
curl -X POST http://localhost:8000/api/v1/hobbies \
  -H "Authorization: Bearer 1|abc123..." \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Reading",
    "description": "Reading books and articles"
  }'

# Response:
# { "data": { "id": 1, "name": "Reading", "description": "Reading books and articles", ... } }

# 7. Add Hobby to User
curl -X POST http://localhost:8000/api/v1/user/hobbies \
  -H "Authorization: Bearer 1|abc123..." \
  -H "Content-Type: application/json" \
  -d '{
    "hobby_id": 1
  }'

# 8. Create Activity
curl -X POST http://localhost:8000/api/v1/activities \
  -H "Authorization: Bearer 1|abc123..." \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Read a chapter",
    "description": "Read one chapter from current book",
    "duration_minutes": 30
  }'

# Response:
# { "data": { "id": 1, "name": "Read a chapter", "description": "Read one chapter from current book", "duration_minutes": 30, ... } }

# 9. List Activities
curl -X GET http://localhost:8000/api/v1/activities \
  -H "Authorization: Bearer 1|abc123..."

# 10. Update Activity
curl -X PUT http://localhost:8000/api/v1/activities/1 \
  -H "Authorization: Bearer 1|abc123..." \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Read two chapters",
    "duration_minutes": 60
  }'

# 11. Delete Activity
curl -X DELETE http://localhost:8000/api/v1/activities/1 \
  -H "Authorization: Bearer 1|abc123..."

# 12. Logout
curl -X POST http://localhost:8000/api/v1/auth/logout \
  -H "Authorization: Bearer 1|abc123..."
```

---

## Hobby CRUD Endpoints

### List Hobbies
**GET** `/hobbies`

**Headers:**
```
Authorization: Bearer YOUR_TOKEN
```

**Response (200):**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Reading",
      "description": "Reading books and articles",
      "created_at": "2026-01-20T10:30:00.000000Z",
      "updated_at": "2026-01-20T10:30:00.000000Z"
    }
  ]
}
```

### Get Hobby Details
**GET** `/hobbies/{hobby}`

**Headers:**
```
Authorization: Bearer YOUR_TOKEN
```

**Response (200):**
```json
{
  "id": 1,
  "name": "Reading",
  "description": "Reading books and articles",
  "activities": [],
  "created_at": "2026-01-20T10:30:00.000000Z",
  "updated_at": "2026-01-20T10:30:00.000000Z"
}
```

### Create Hobby
**POST** `/hobbies`

**Headers:**
```
Authorization: Bearer YOUR_TOKEN
```

**Request Body:**
```json
{
  "name": "Reading",
  "description": "Reading books and articles"
}
```

**Response (201):**
```json
{
  "data": {
    "id": 1,
    "name": "Reading",
    "description": "Reading books and articles",
    "created_at": "2026-01-20T10:30:00.000000Z",
    "updated_at": "2026-01-20T10:30:00.000000Z"
  }
}
```

### Update Hobby
**PUT** `/hobbies/{hobby}`

**Headers:**
```
Authorization: Bearer YOUR_TOKEN
```

**Request Body:**
```json
{
  "name": "Reading",
  "description": "Reading books, articles, and blogs"
}
```

**Response (200):**
```json
{
  "data": {
    "id": 1,
    "name": "Reading",
    "description": "Reading books, articles, and blogs",
    "created_at": "2026-01-20T10:30:00.000000Z",
    "updated_at": "2026-01-21T15:45:00.000000Z"
  }
}
```

### Delete Hobby
**DELETE** `/hobbies/{hobby}`

**Headers:**
```
Authorization: Bearer YOUR_TOKEN
```

**Response (200):**
```json
{
  "message": "Hobby deleted successfully"
}
```

---

## Activity CRUD Endpoints

### List Activities
**GET** `/activities`

**Headers:**
```
Authorization: Bearer YOUR_TOKEN
```

**Response (200):**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Read a chapter",
      "description": "Read one chapter from current book",
      "duration_minutes": 30,
      "created_at": "2026-01-20T10:30:00.000000Z",
      "updated_at": "2026-01-20T10:30:00.000000Z"
    }
  ]
}
```

### Create Activity
**POST** `/activities`

**Headers:**
```
Authorization: Bearer YOUR_TOKEN
```

**Request Body:**
```json
{
  "name": "Read a chapter",
  "description": "Read one chapter from current book",
  "duration_minutes": 30
}
```

**Response (201):**
```json
{
  "data": {
    "id": 1,
    "name": "Read a chapter",
    "description": "Read one chapter from current book",
    "duration_minutes": 30,
    "created_at": "2026-01-20T10:30:00.000000Z",
    "updated_at": "2026-01-20T10:30:00.000000Z"
  }
}
```

### Update Activity
**PUT** `/activities/{activity}`

**Headers:**
```
Authorization: Bearer YOUR_TOKEN
```

**Request Body:**
```json
{
  "name": "Read two chapters",
  "duration_minutes": 60
}
```

**Response (200):**
```json
{
  "data": {
    "id": 1,
    "name": "Read two chapters",
    "description": "Read one chapter from current book",
    "duration_minutes": 60,
    "created_at": "2026-01-20T10:30:00.000000Z",
    "updated_at": "2026-01-21T15:45:00.000000Z"
  }
}
```

### Delete Activity
**DELETE** `/activities/{activity}`

**Headers:**
```
Authorization: Bearer YOUR_TOKEN
```

**Response (200):**
```json
{
  "message": "Activity deleted successfully"
}
```

---

## Error Responses

### Validation Error (422)
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["The email has already been taken."],
    "password": ["The password must be at least 8 characters."]
  }
}
```

### Authentication Error (401)
```json
{
  "message": "Invalid credentials"
}
```

### Unauthorized (401)
```json
{
  "message": "Unauthenticated."
}
```

### Provider Mismatch (422)
```json
{
  "message": "Please login using google"
}
```

---

## Quick Tips

### Token Management
- Store tokens securely (secure storage, keychain)
- Include token in `Authorization: Bearer {token}` header
- Logout invalidates ALL user tokens
- Token format: `{id}|{string}` (e.g., `1|abc123...`)

### Best Practices
- Use HTTPS in production
- Handle 401 errors with re-authentication
- Validate data on client before API call
- Cache user data to reduce API calls
- Implement request timeout (30s recommended)

### Field Constraints
- **Email**: Must be unique across all users
- **Password**: Minimum 8 characters (no maximum specified)
- **Country**: Maximum 100 characters
- **Screen Goal Hours**: 1-168 (weekly hours, max 1 week)
- **Gender**: Only accepts: `male`, `female`, `other`
- **Age Range**: Only accepts: `5-18`, `18-30`, `30-45`, `45+`

---

## Environment Setup

### Development
```
BASE_URL=http://localhost:8000/api/v1
```

### Production
```
BASE_URL=https://api.pivot-app.com/api/v1
```

---

## Rate Limiting

Currently no rate limits enforced. Recommended client-side throttling:
- Maximum 100 requests per minute
- Implement exponential backoff for retries
- Cache responses when possible

---

## Support

- **Documentation**: Full docs at `/Docs/PIVOT_API_DOCUMENTATION.md`
- **Email**: support@pivot-app.com
- **Issues**: GitHub Issues

---

**Last Updated:** January 25, 2026  
**API Version:** 1.1
