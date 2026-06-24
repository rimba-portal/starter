# RIMBA Identity Package Architecture

## Purpose

The RIMBA Identity package provides a unified authentication framework for Laravel and Filament applications.

The package is designed to support multiple authentication factors and authentication methods through a configurable authentication pipeline.

Version 1 focuses on:

* Face Recognition
* PIN Verification

Future versions may support:

* Password Authentication
* TOTP Authentication
* QR Authentication
* RFID Authentication
* NFC Authentication
* SSO Authentication
* Passkeys / WebAuthn
* Biometric Authentication

The package should be named:

```text
rimba/identity

packages/
└── rimba/
    └── face-auth/
        ├── src/
        │   ├── FaceAuthServiceProvider.php
        │   ├── Contracts/
        │   │   └── FaceVerifier.php
        │   ├── Services/
        │   │   ├── FaceApiVerifier.php
        │   │   ├── NullVerifier.php
        │   │   └── LoginPipeline.php
        │   ├── Http/
        │   │   ├── Controllers/
        │   │   │   ├── StartLoginController.php
        │   │   │   ├── VerifyFaceController.php
        │   │   │   ├── VerifyPinController.php
        │   │   │   └── CompleteLoginController.php
        │   │   ├── Middleware/
        │   │   │   └── FaceAuthSessionMiddleware.php
        │   │   └── Requests/
        │   ├── Models/
        │   │   ├── FaceProfile.php
        │   │   └── LoginAttempt.php
        │   ├── Filament/
        │   │   ├── Pages/
        │   │   │   └── FaceLogin.php
        │   │   ├── Widgets/
        │   │   └── Components/
        │   ├── Events/
        │   ├── Listeners/
        │   ├── Actions/
        │   └── Facades/
        │
        ├── database/
        │   └── migrations/
        │
        ├── resources/
        │   ├── js/
        │   │   ├── face-auth.js
        │   │   └── face-api.min.js
        │   ├── views/
        │   └── models/
        │       ├── tiny_face_detector_model
        │       ├── face_landmark_68_model
        │       └── face_recognition_model
        │
        ├── routes/
        │   └── web.php
        │
        ├── config/
        │   └── face-auth.php
        │
        └── composer.json
```

---

# Design Principles

## Authentication Factor Architecture

Authentication is not tied to a single login method.

Instead, authentication is performed through a sequence of authentication factors.

Example:

```text
Face
↓
PIN
↓
Login
```

Future:

```text
Face
↓
PIN
↓
TOTP
↓
Login
```

or

```text
RFID
↓
PIN
↓
Login
```

No database redesign should be required when adding new factors.

---

# Authentication Pipeline

Current Pipeline:

```text
Staff ID
↓
Resolve User
↓
Face Verification
↓
PIN Verification
↓
Laravel Authentication
↓
Filament Redirect
```

Future Pipeline Examples:

```text
Face
↓
PIN
↓
TOTP
↓
Login
```

```text
RFID
↓
PIN
↓
Login
```

```text
QR
↓
PIN
↓
Login
```

The pipeline must be configurable.

Example:

```php
'pipeline' => [
    'face',
    'pin',
];
```

---

# ISA-95 Alignment

The package must align with the RIMBA ISA-95 personnel model.

Identity profiles are attached using polymorphic relationships.

Supported entities:

* User
* Staff
* Contractor
* Vendor Representative
* Visitor
* Operator
* Supervisor
* Manager

Identity is attached through:

```php
morphs('personable')
```

---

# Database Design

## identity_profiles

Stores authentication information.

Fields:

```text
id
personable_type
personable_id
face_image
pin_hash
is_enabled
created_at
updated_at
```

Purpose:

* Store face reference image
* Store PIN hash
* Enable / disable identity access

---

## identity_attempts

Stores audit logs.

Fields:

```text
id
user_id
staff_id
face_passed
pin_passed
ip
attempted_at
created_at
updated_at
```

Purpose:

* Login auditing
* Security monitoring
* Failed attempt tracking

---

# Core Contracts

## AuthFactor

Represents one authentication factor.

Examples:

* FaceDriver
* PinDriver
* TotpDriver
* RfidDriver

Contract:

```php
interface AuthFactor
{
    public function verify(
        Authenticatable $user,
        array $payload
    ): bool;
}
```

---

# Drivers

## FaceDriver

Responsibilities:

* Verify face match result
* Validate threshold
* Approve or reject face verification

Current implementation:

* Browser face-api.js

Future implementation:

* Python Service
* AWS Rekognition
* Azure Face API
* Google Vision

The driver must be swappable through configuration.

---

## PinDriver

Responsibilities:

* Verify PIN hash
* Approve or reject PIN authentication

Implementation:

```php
Hash::check(...)
```

---

# Identity Manager

IdentityManager resolves authentication factors.

Example:

```php
face
↓
FaceDriver

pin
↓
PinDriver
```

Future drivers should be automatically discoverable.

---

# Face Recognition

Version 1 uses:

```text
face-api.js
```

Flow:

```text
Load Stored Face
↓
Capture Webcam Image
↓
Generate Descriptor
↓
Calculate Distance
↓
Compare Threshold
```

Current threshold:

```php
0.50
```

Configurable:

```php
'face_threshold' => 0.50
```

---

# Security Notes

Version 1 performs matching in the browser.

This is acceptable for:

* Internal Portals
* Factory Kiosks
* Staff Terminals

Future versions should support:

```text
Camera
↓
Snapshot
↓
Laravel API
↓
Python Face Recognition Service
↓
Verification Result
```

Server-side verification is preferred for higher security environments.

---

# Filament Integration

A custom Filament login page should be provided.

Page:

```text
IdentityLogin
```

Workflow:

```text
Staff ID
↓
Face Match
↓
PIN
↓
Login
```

The package should support:

* Filament v5
* Multiple Panels
* Panel-specific authentication

---

# User Integration

Models gain identity support through:

```php
HasIdentityProfile
```

Example:

```php
class User extends Authenticatable
{
    use HasIdentityProfile;
}
```

---

# Configuration

Example:

```php
return [

    'guard' => 'web',

    'staff_id_column' => 'staff_id',

    'face_threshold' => 0.50,

    'pipeline' => [
        'face',
        'pin',
    ],

];
```

---

# Future Roadmap

## Version 1

Features:

* Staff ID lookup
* Face authentication
* PIN authentication
* Filament login page
* Login audit logs

---

## Version 2

Features:

* Password factor
* TOTP factor
* Recovery codes

---

## Version 3

Features:

* QR factor
* RFID factor
* NFC factor

---

## Version 4

Features:

* WebAuthn
* Passkeys
* Hardware tokens

---

# RIMBA Vision

The long-term goal is not a Face Login package.

The long-term goal is a complete Identity and Access Management framework.

The package should evolve into:

```text
rimba/identity
├── Face Authentication
├── PIN Authentication
├── Password Authentication
├── TOTP Authentication
├── QR Authentication
├── RFID Authentication
├── NFC Authentication
├── Passkeys
├── SSO Authentication
└── Identity Audit Framework
```

All authentication methods should plug into the same configurable authentication pipeline without requiring database redesign or application refactoring.
