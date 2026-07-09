# Bites Attributing

## Overview

The Attributing package provides a flexible mechanism for attaching dynamic attributes to domain models without requiring schema changes.

Rather than creating many nullable columns on every table, attributes are stored in dedicated attribute tables and attached through Laravel polymorphic relationships.

The package intentionally separates attributes into three real-world archetypes:

```text
WHO    → Person
WHAT   → Thing
WHERE  → Place
```

This makes the system easier to understand and aligns naturally with business domains such as HR, Asset Management, Facilities Management, Manufacturing, and ERP systems.

---

# Core Concepts

## Person

Represents a human being or a human specification.

Examples:

```text
Staff
User
Applicant
Candidate
Contractor
Job Post
```

Typical attributes:

```text
gender
race
religion
nationality
height
weight
blood_type
date_of_birth
education_level
driving_license
```

Stored in:

```text
person_attributes
```

Model:

```php
PersonAttribute
```

Trait:

```php
HasPersonAttributes
```

---

## Thing

Represents a physical or logical object.

Examples:

```text
Asset
Equipment
Vehicle
Machine
Tool
Device
Product
Material
Document
```

Typical attributes:

```text
brand
model
serial_number
color
capacity
dimensions
power_rating
manufacturer
warranty_period
```

Stored in:

```text
thing_attributes
```

Model:

```php
ThingAttribute
```

Trait:

```php
HasThingAttributes
```

---

## Place

Represents a physical location.

Examples:

```text
Site
Building
Warehouse
Floor
Room
Workstation
Location
```

Typical attributes:

```text
latitude
longitude
area
floor_count
capacity
address
postcode
elevation
```

Stored in:

```text
place_attributes
```

Model:

```php
LocationAttribute
```

Trait:

```php
HasLocationAttributes
```

---

# Why Three Attribute Types?

Instead of using a generic table:

```text
attributes
```

the package separates attributes by domain meaning.

Benefits:

- Easier for developers to understand.
- Better business terminology.
- Simpler reporting.
- Cleaner data ownership.
- Future flexibility for domain-specific validation and rules.

Example:

```text
Staff
 └─ Person Attributes

Forklift
 └─ Thing Attributes

Warehouse A
 └─ Place Attributes
```

---

# Database Structure

## person_attributes

```text
id
key
value
attributable_type
attributable_id
created_at
updated_at
```

## thing_attributes

```text
id
key
value
attributable_type
attributable_id
created_at
updated_at
```

## place_attributes

```text
id
key
value
attributable_type
attributable_id
created_at
updated_at
```

---

# Relationship Model

```text
Staff
 └─ morphMany(PersonAttribute)

Vehicle
 └─ morphMany(ThingAttribute)

Building
 └─ morphMany(LocationAttribute)
```

Example:

```text
Staff #1001
 ├─ gender = Male
 ├─ religion = Islam
 └─ nationality = Malaysia

Vehicle #V001
 ├─ color = Red
 ├─ engine_capacity = 2000cc
 └─ transmission = Automatic

Building #B001
 ├─ floor_count = 5
 ├─ area = 2500 sqm
 └─ postcode = 42700
```

---

# Philosophy

The package models business entities using three fundamental concepts:

```text
Person = Who
Thing  = What
Place  = Where
```

This allows developers to immediately understand the purpose of an attribute without relying on technical terminology such as:

```text
Object
Resource
Entity
```

The result is a domain model that is intuitive, business-friendly, and scalable across the entire ecosystem.

```text
Who    → Person
What   → Thing
Where  → Place
```

These three categories form the foundation of the Attributing package.