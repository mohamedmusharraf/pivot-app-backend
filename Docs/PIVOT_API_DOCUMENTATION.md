# Pivot App Backend - REST API Documentation

**Version:** 1.2  
**Base URL:** `/api/v1`  
**Authentication:** Bearer Token (Laravel Sanctum)

---

## Table of Contents

1. [Authentication](#1-authentication)
2. [User Profile](#2-user-profile)
3. [Hobbies](#3-hobbies)
4. [User Hobbies](#4-user-hobbies)
5. [Activities](#5-activities)
6. [Additional Resources](#6-additional-resources)

---

## Response Format

All API responses follow this standard format:

```json
{
    "message": "Success message or error description",
    "data": {} // or []
}
```

### HTTP Status Codes

| Code | Description      |
| ---- | ---------------- |
| 200  | Success          |
| 201  | Created          |
| 400  | Bad Request      |
| 401  | Unauthorized     |
| 404  | Not Found        |
| 422  | Validation Error |
| 500  | Server Error     |

---

## 1. Authentication

### 1.1 Register

Creates a new user account.

**Endpoint:** `POST /auth/register`  
**Auth Required:** No

**Request Body:**

```json
{
    "name": "string (optional, max: 255)",
    "email": "string (required, email, unique)",
    "password": "string (required, min: 8)"
}
```

**Response (Success - 201):**

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

**Validation Rules:**

- `name`: Optional, string, maximum 255 characters
- `email`: Required, valid email format, must be unique in the system
- `password`: Required, minimum 8 characters

**cURL Example:**

```bash
curl -X POST http://localhost:8000/api/v1/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123"
  }'
```

---

### 1.2 Login

Authenticates user and returns access token.

**Endpoint:** `POST /auth/login`  
**Auth Required:** No

**Request Body:**

```json
{
    "email": "string (required, email)",
    "password": "string (required)"
}
```

**Response (Success - 200):**

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

**Error Responses:**

**401 Unauthorized (Invalid Credentials):**

```json
{
    "message": "Invalid credentials"
}
```

**422 Unprocessable Entity (Different Provider):**

```json
{
    "message": "Please login using google"
}
```

**Validation Rules:**

- `email`: Required, valid email format
- `password`: Required

**cURL Example:**

```bash
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "password123"
  }'
```

**Notes:**

- User's `last_login_at` timestamp is updated upon successful login
- Only users registered with email provider can login with this endpoint
- Users registered via OAuth (Google, Facebook, etc.) must use their respective OAuth flow

---

### 1.3 Logout

Logs out current user and invalidates all tokens.

**Endpoint:** `POST /auth/logout`  
**Auth Required:** Yes

**Request Headers:**

```
Authorization: Bearer YOUR_TOKEN_HERE
```

**Response (Success - 200):**

```json
{
    "message": "Logged out successfully"
}
```

**cURL Example:**

```bash
curl -X POST http://localhost:8000/api/v1/auth/logout \
  -H "Authorization: Bearer 1|abcdef123456..." \
  -H "Content-Type: application/json"
```

**Notes:**

- This endpoint deletes ALL active tokens for the authenticated user
- After logout, the token becomes invalid and cannot be reused
- User must login again to get a new token

---

### 1.4 Get Current User

Retrieves currently authenticated user information.

**Endpoint:** `GET /user/current-user`  
**Auth Required:** Yes

**Request Headers:**

```
Authorization: Bearer YOUR_TOKEN_HERE
```

**Response (Success - 200):**

```json
{
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "provider": "email",
    "created_at": "2026-01-20T10:30:00.000000Z"
}
```

**cURL Example:**

```bash
curl -X GET http://localhost:8000/api/v1/user/current-user \
  -H "Authorization: Bearer 1|abcdef123456..."
```

---

### 1.5 Forgot Password

Sends a password reset link or token (route requires authentication per current routes).

**Endpoint:** `POST /forgot-password`
**Auth Required:** Yes

**Request Body:**

```json
{
    "email": "string (required, email)"
}
```

**Response (200):**

```json
{
    "message": "Password reset link sent"
}
```

### 1.6 Reset Password

Resets a user's password using a token.

**Endpoint:** `POST /reset-password`
**Auth Required:** Yes

**Request Body:**

```json
{
    "token": "string (required)",
    "email": "string (required, email)",
    "password": "string (required, min: 8)",
    "password_confirmation": "string (required, same as password)"
}
```

**Response (200):**

```json
{
    "message": "Password has been reset"
}
```

**Notes:**

- Returns the user associated with the provided bearer token
- Useful for verifying token validity and getting user details after login

---

## 2. User Profile

### 2.1 Create or Update Profile

Creates a new profile or updates existing profile for the authenticated user.

**Endpoint:** `POST /profile`  
**Auth Required:** Yes

**Request Headers:**

```
Authorization: Bearer YOUR_TOKEN_HERE
Content-Type: application/json
```

**Request Body:**

```json
{
    "country": "string (required, max: 100)",
    "gender": "string (required, enum: male|female|other)",
    "age_range": "string (required, enum: 5-18|18-30|30-45|45+)",
    "screen_goal_hours": "integer (required, min: 1, max: 168)",
    "onboarding_completed": "boolean (optional, default: false)"
}
```

**Response (Success - 200):**

```json
{
    "message": "Profile saved successfully",
    "profile": {
        "country": "United States",
        "gender": "male",
        "age_range": "18-30",
        "screen_goal_hours": 40,
        "onboarding_completed": true
    }
}
```

**Field Descriptions:**

| Field                  | Type    | Required | Validation                    | Description                                                |
| ---------------------- | ------- | -------- | ----------------------------- | ---------------------------------------------------------- |
| `country`              | string  | Yes      | max: 100                      | User's country of residence                                |
| `gender`               | string  | Yes      | enum: male, female, other     | User's gender identity                                     |
| `age_range`            | string  | Yes      | enum: 5-18, 18-30, 30-45, 45+ | User's age range category                                  |
| `screen_goal_hours`    | integer | Yes      | min: 1, max: 168              | Weekly screen time goal in hours (max: 1 week = 168 hours) |
| `onboarding_completed` | boolean | No       | true/false                    | Whether user has completed onboarding process              |

**cURL Example:**

```bash
curl -X POST http://localhost:8000/api/v1/user/profile \
  -H "Authorization: Bearer 1|abcdef123456..." \
  -H "Content-Type: application/json" \
  -d '{
    "country": "United States",
    "gender": "male",
    "age_range": "18-30",
    "screen_goal_hours": 40,
    "onboarding_completed": true
  }'
```

**Validation Error Example (422):**

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "gender": ["The selected gender is invalid."],
        "screen_goal_hours": [
            "The screen goal hours must be at least 1.",
            "The screen goal hours must not be greater than 168."
        ]
    }
}
```

**Notes:**

- This endpoint uses `updateOrCreate` logic - it will create a new profile if one doesn't exist, or update the existing one
- Each user can only have one profile
- The `onboarding_completed` field defaults to `false` if not provided
- Screen goal hours represents weekly hours, so maximum is 168 (24 hours × 7 days)

---

## 3. Hobbies

### 3.1 List All Hobbies

Retrieves a list of all hobbies.

**Endpoint:** `GET /hobbies`  
**Auth Required:** Yes

**Request Headers:**

```
Authorization: Bearer YOUR_TOKEN_HERE
```

**Response (Success - 200):**

```json
{
    "data": [
        {
            "id": 1,
            "name": "Reading",
            "description": "Reading books and articles",
            "created_at": "2026-01-20T10:30:00.000000Z",
            "updated_at": "2026-01-20T10:30:00.000000Z"
        },
        {
            "id": 2,
            "name": "Gaming",
            "description": "Playing video games",
            "created_at": "2026-01-20T10:31:00.000000Z",
            "updated_at": "2026-01-20T10:31:00.000000Z"
        }
    ]
}
```

**cURL Example:**

```bash
curl -X GET http://localhost:8000/api/v1/hobbies \
  -H "Authorization: Bearer 1|abcdef123456..."
```

---

### 3.2 Get Hobby Details

Retrieves details of a specific hobby.

**Endpoint:** `GET /hobbies/{hobby}`  
**Auth Required:** Yes

**Path Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| `hobby` | integer | Hobby ID |

**Request Headers:**

```
Authorization: Bearer YOUR_TOKEN_HERE
```

**Response (Success - 200):**

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

**Error Response (404):**

```json
{
    "message": "Hobby not found"
}
```

**cURL Example:**

```bash
curl -X GET http://localhost:8000/api/v1/hobbies/1 \
  -H "Authorization: Bearer 1|abcdef123456..."
```

---

### 3.3 Create Hobby

Creates a new hobby.

**Endpoint:** `POST /hobbies`  
**Auth Required:** Yes

**Request Headers:**

```
Authorization: Bearer YOUR_TOKEN_HERE
Content-Type: application/json
```

**Request Body:**

```json
{
    "name": "string (required, max: 255)",
    "description": "string (optional, max: 1000)"
}
```

**Response (Success - 201):**

```json
{
    "message": "Hobby created successfully",
    "data": {
        "id": 3,
        "name": "Painting",
        "description": "Creating artworks",
        "created_at": "2026-01-25T14:20:00.000000Z",
        "updated_at": "2026-01-25T14:20:00.000000Z"
    }
}
```

**cURL Example:**

```bash
curl -X POST http://localhost:8000/api/v1/hobbies \
  -H "Authorization: Bearer 1|abcdef123456..." \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Painting",
    "description": "Creating artworks"
  }'
```

---

### 3.4 Update Hobby

Updates an existing hobby.

**Endpoint:** `PUT /hobbies/{hobby}`  
**Auth Required:** Yes

**Path Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| `hobby` | integer | Hobby ID |

**Request Headers:**

```
Authorization: Bearer YOUR_TOKEN_HERE
Content-Type: application/json
```

**Request Body:**

```json
{
    "name": "string (optional, max: 255)",
    "description": "string (optional, max: 1000)"
}
```

**Response (Success - 200):**

```json
{
    "message": "Hobby updated successfully",
    "data": {
        "id": 3,
        "name": "Digital Painting",
        "description": "Creating digital artworks and illustrations",
        "created_at": "2026-01-25T14:20:00.000000Z",
        "updated_at": "2026-01-25T14:25:00.000000Z"
    }
}
```

**cURL Example:**

```bash
curl -X PUT http://localhost:8000/api/v1/hobbies/3 \
  -H "Authorization: Bearer 1|abcdef123456..." \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Digital Painting",
    "description": "Creating digital artworks and illustrations"
  }'
```

---

### 3.5 Delete Hobby

Deletes a hobby.

**Endpoint:** `DELETE /hobbies/{hobby}`  
**Auth Required:** Yes

**Path Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| `hobby` | integer | Hobby ID |

**Request Headers:**

```
Authorization: Bearer YOUR_TOKEN_HERE
```

**Response (Success - 200):**

```json
{
    "message": "Hobby deleted successfully"
}
```

**Error Response (404):**

```json
{
    "message": "Hobby not found"
}
```

**cURL Example:**

```bash
curl -X DELETE http://localhost:8000/api/v1/hobbies/3 \
  -H "Authorization: Bearer 1|abcdef123456..."
```

---

## 4. User Hobbies

### 4.1 Add Hobby to User

Adds a hobby to the authenticated user's hobby list.

**Endpoint:** `POST /user/hobbies`  
**Auth Required:** Yes

**Request Headers:**

```
Authorization: Bearer YOUR_TOKEN_HERE
Content-Type: application/json
```

**Request Body:**

```json
{
    "hobby_id": "integer (required)"
}
```

**Response (Success - 201):**

```json
{
    "message": "Hobby added to user successfully",
    "data": {
        "id": 1,
        "user_id": 1,
        "hobby_id": 2,
        "created_at": "2026-01-25T14:30:00.000000Z",
        "updated_at": "2026-01-25T14:30:00.000000Z"
    }
}
```

**Validation Error (422):**

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "hobby_id": ["The hobby id field is required."]
    }
}
```

**cURL Example:**

```bash
curl -X POST http://localhost:8000/api/v1/user/hobbies \
  -H "Authorization: Bearer 1|abcdef123456..." \
  -H "Content-Type: application/json" \
  -d '{
    "hobby_id": 2
  }'
```

---

## 5. Activities

### 5.1 List All Activities

Retrieves a list of all activities for the authenticated user.

**Endpoint:** `GET /activities`  
**Auth Required:** Yes

**Request Headers:**

```
Authorization: Bearer YOUR_TOKEN_HERE
```

**Response (Success - 200):**

```json
{
    "data": [
        {
            "id": 1,
            "user_id": 1,
            "name": "Read a chapter",
            "description": "Read one chapter from current book",
            "duration_minutes": 30,
            "created_at": "2026-01-20T10:30:00.000000Z",
            "updated_at": "2026-01-20T10:30:00.000000Z"
        },
        {
            "id": 2,
            "user_id": 1,
            "name": "Play online game",
            "description": "Play an hour of online multiplayer games",
            "duration_minutes": 60,
            "created_at": "2026-01-20T10:35:00.000000Z",
            "updated_at": "2026-01-20T10:35:00.000000Z"
        }
    ]
}
```

**cURL Example:**

```bash
curl -X GET http://localhost:8000/api/v1/activities \
  -H "Authorization: Bearer 1|abcdef123456..."
```

---

### 5.2 Create Activity

Creates a new activity for the authenticated user.

**Endpoint:** `POST /activities`  
**Auth Required:** Yes

**Request Headers:**

```
Authorization: Bearer YOUR_TOKEN_HERE
Content-Type: application/json
```

**Request Body:**

```json
{
    "name": "string (required, max: 255)",
    "description": "string (optional, max: 1000)",
    "duration_minutes": "integer (optional, min: 1)"
}
```

**Response (Success - 201):**

```json
{
    "message": "Activity created successfully",
    "data": {
        "id": 3,
        "user_id": 1,
        "name": "Sketch for 45 minutes",
        "description": "Freehand sketching practice",
        "duration_minutes": 45,
        "created_at": "2026-01-25T15:00:00.000000Z",
        "updated_at": "2026-01-25T15:00:00.000000Z"
    }
}
```

**cURL Example:**

```bash
curl -X POST http://localhost:8000/api/v1/activities \
  -H "Authorization: Bearer 1|abcdef123456..." \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Sketch for 45 minutes",
    "description": "Freehand sketching practice",
    "duration_minutes": 45
  }'
```

---

### 5.3 Update Activity

Updates an existing activity.

**Endpoint:** `PUT /activities/{activity}`  
**Auth Required:** Yes

**Path Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| `activity` | integer | Activity ID |

**Request Headers:**

```
Authorization: Bearer YOUR_TOKEN_HERE
Content-Type: application/json
```

**Request Body:**

```json
{
    "name": "string (optional, max: 255)",
    "description": "string (optional, max: 1000)",
    "duration_minutes": "integer (optional, min: 1)"
}
```

**Response (Success - 200):**

```json
{
    "message": "Activity updated successfully",
    "data": {
        "id": 3,
        "user_id": 1,
        "name": "Sketch for one hour",
        "description": "Advanced sketching practice with reference",
        "duration_minutes": 60,
        "created_at": "2026-01-25T15:00:00.000000Z",
        "updated_at": "2026-01-25T15:10:00.000000Z"
    }
}
```

**cURL Example:**

```bash
curl -X PUT http://localhost:8000/api/v1/activities/3 \
  -H "Authorization: Bearer 1|abcdef123456..." \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Sketch for one hour",
    "description": "Advanced sketching practice with reference",
    "duration_minutes": 60
  }'
```

---

### 5.4 Delete Activity

Deletes an activity.

**Endpoint:** `DELETE /activities/{activity}`  
**Auth Required:** Yes

**Path Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| `activity` | integer | Activity ID |

**Request Headers:**

```
Authorization: Bearer YOUR_TOKEN_HERE
```

**Response (Success - 200):**

```json
{
    "message": "Activity deleted successfully"
}
```

**Error Response (404):**

```json
{
    "message": "Activity not found"
}
```

**cURL Example:**

```bash
curl -X DELETE http://localhost:8000/api/v1/activities/3 \
  -H "Authorization: Bearer 1|abcdef123456..."
```

---

## 6. Additional Resources

### Common Request Headers

All authenticated endpoints require:

```
Authorization: Bearer YOUR_TOKEN_HERE
Content-Type: application/json
Accept: application/json
```

---

### Authentication Flow Example

```bash
# Step 1: Register a new user
curl -X POST http://localhost:8000/api/v1/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123"
  }'

# Response:
{
  "token": "1|abc123def456...",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "provider": "email",
    "created_at": "2026-01-20T10:30:00.000000Z"
  }
}

# Step 2: Create user profile
curl -X POST http://localhost:8000/api/v1/user/profile \
  -H "Authorization: Bearer 1|abc123def456..." \
  -H "Content-Type: application/json" \
  -d '{
    "country": "United States",
    "gender": "male",
    "age_range": "18-30",
    "screen_goal_hours": 40,
    "onboarding_completed": true
  }'

# Step 3: Get current user information
curl -X GET http://localhost:8000/api/v1/user/current-user \
  -H "Authorization: Bearer 1|abc123def456..."

# Step 4: Logout when done
curl -X POST http://localhost:8000/api/v1/auth/logout \
  -H "Authorization: Bearer 1|abc123def456..."
```

---

### Error Handling

#### Validation Errors (422)

When request data fails validation:

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "email": ["The email has already been taken."],
        "password": ["The password must be at least 8 characters."]
    }
}
```

#### Authentication Errors (401)

When token is missing or invalid:

```json
{
    "message": "Unauthenticated."
}
```

#### Authorization Errors (403)

When user doesn't have permission:

```json
{
    "message": "This action is unauthorized."
}
```

#### Not Found Errors (404)

When resource doesn't exist:

```json
{
    "message": "Resource not found."
}
```

#### Server Errors (500)

When server encounters an error:

```json
{
    "message": "Server Error"
}
```

---

### Token Management

**Token Lifecycle:**

1. Token is created during registration or login
2. Token must be included in `Authorization` header for protected endpoints
3. Token remains valid until explicitly revoked via logout
4. Logout deletes ALL tokens for the user

**Token Format:**

```
Authorization: Bearer {token_id}|{token_string}
```

Example:

```
Authorization: Bearer 1|abcdefghijklmnopqrstuvwxyz123456789
```

---

### Best Practices

1. **Secure Token Storage**
    - Store tokens securely on client side (e.g., secure storage, keychain)
    - Never expose tokens in logs or URLs
    - Use HTTPS in production

2. **Error Handling**
    - Always check HTTP status codes
    - Parse error messages for user-friendly display
    - Handle 401 errors by redirecting to login

3. **API Versioning**
    - All endpoints are prefixed with `/api/v1`
    - Version is included in URL for backward compatibility

4. **Request Headers**
    - Always include `Content-Type: application/json` for POST/PUT requests
    - Include `Accept: application/json` to ensure JSON responses

5. **Password Security**
    - Minimum 8 characters required
    - Use strong passwords with mix of characters
    - Never store passwords in plain text on client

---

### Database Schema

#### Users Table

| Column          | Type         | Nullable | Description                             |
| --------------- | ------------ | -------- | --------------------------------------- |
| `id`            | bigint       | No       | Primary key                             |
| `name`          | varchar(255) | Yes      | User's full name                        |
| `email`         | varchar(255) | No       | User's email (unique)                   |
| `password`      | varchar(255) | No       | Hashed password                         |
| `provider`      | varchar(50)  | Yes      | Auth provider (email, google, facebook) |
| `last_login_at` | timestamp    | Yes      | Last login timestamp                    |
| `created_at`    | timestamp    | No       | Record creation time                    |
| `updated_at`    | timestamp    | No       | Record update time                      |

#### User Profiles Table

| Column                 | Type         | Nullable | Description                        |
| ---------------------- | ------------ | -------- | ---------------------------------- |
| `id`                   | bigint       | No       | Primary key                        |
| `user_id`              | bigint       | No       | Foreign key to users table         |
| `country`              | varchar(100) | No       | User's country                     |
| `gender`               | enum         | No       | male, female, other                |
| `age_range`            | enum         | No       | 5-18, 18-30, 30-45, 45+            |
| `screen_goal_hours`    | integer      | No       | Weekly screen time goal (1-168)    |
| `onboarding_completed` | boolean      | No       | Onboarding status (default: false) |
| `created_at`           | timestamp    | No       | Record creation time               |
| `updated_at`           | timestamp    | No       | Record update time                 |

---

### Postman Collection

To import this API into Postman:

1. Create a new collection named "Pivot App API"
2. Set base URL variable: `{{base_url}} = http://localhost:8000/api/v1`
3. Set token variable after login: `{{token}} = YOUR_TOKEN`
4. Import endpoints from this documentation

**Environment Variables:**

```json
{
    "base_url": "http://localhost:8000/api/v1",
    "token": ""
}
```

---

### Rate Limiting

Currently, no specific rate limits are enforced. However, best practices recommend:

- Implement client-side request throttling
- Cache responses when appropriate
- Avoid excessive API calls in loops
- Use pagination for large datasets (when implemented)

---

### Changelog

**Version 1.1** (January 25, 2026)

- Added Hobby management endpoints (list, retrieve, create, update, delete)
- Added User Hobbies endpoint (add hobby to user)
- Added Activity management endpoints (list, create, update, delete)

**Version 1.0** (January 20, 2026)

- Initial API release
- Authentication endpoints (register, login, logout)
- User profile management
- Current user retrieval

---

### Support & Contact

For API support, issues, or feature requests:

- Email: support@pivot-app.com
- Documentation: [API Documentation](https://docs.pivot-app.com)
- GitHub: [Issues](https://github.com/pivot-app/backend/issues)

---

**Last Updated:** February 2, 2026
