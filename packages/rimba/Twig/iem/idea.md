# Industrial Engineering Manufacturing ERP - Filament Navigation Structure

This document outlines the navigation architecture, labels, descriptions, and database requirements for a Laravel Filament application specialized in Industrial Engineering (IE).

---

## 📦 Group 1: Floor Setup
* **Filament Group Property:** `protected static ?string $navigationGroup = 'Floor Setup';`
* **Purpose:** Maps physical infrastructure, assets, and plant topology. This forms the relational foundation for all other modules.

### 1. Locations
* **Filament Label:** `Locations`
* **Description:** Tracks physical plant sites, manufacturing lines, warehouse zones, and physical storage boundaries.
* **Key Database Fields:** `location_code`, `name`, `floor_dimensions_sqm`, `storage_capacity_units`.

### 2. Work Cells
* **Filament Label:** `Work Cells`
* **Description:** Manages individual manufacturing stations where distinct value-added operations occur.
* **Key Database Fields:** `cell_code`, `name`, `work_type` (CNC, Assembly, etc.), `target_units_per_hour`, `is_active`.

### 3. Equipment Register
* **Filament Label:** `Equipment`
* **Description:** Master database of all factory machinery, capital assets, and connected PLC/IoT data streams.
* **Key Database Fields:** `asset_tag`, `name`, `model_number`, `ideal_cycle_time_seconds`, `utility_rating_kw`, `status`.

### 4. Tooling & Fixtures
* **Filament Label:** `Tooling & Dies`
* **Description:** Tracks customizable tooling components that degrade and require maintenance independently of machinery.
* **Key Database Fields:** `tool_id`, `name`, `stroke_counter`, `maintenance_alert_threshold`, `current_work_cell_id`.

---

## ⚡ Group 2: Process IE
* **Filament Group Property:** `protected static ?string $navigationGroup = 'Process IE';`
* **Purpose:** Drives workflow optimization, minimizes waste (Muda), and calculates operational performance indices.

### 1. Routings & Steps
* **Filament Label:** `Routings`
* **Description:** Defines the linear sequence of production steps a specific product SKU must navigate across work cells.
* **Key Database Fields:** `product_sku`, `sequence_number`, `work_cell_id`, `setup_changeover_time_minutes`.

### 2. Time & Motion Studies
* **Filament Label:** `Time Studies`
* **Description:** Records granular micro-movements to calculate accurate and fair standard assembly times.
* **Key Database Fields:** `observed_time_seconds`, `operator_performance_rating_percentage`, `fatigue_allowance_percentage`, `calculated_standard_time_seconds`.

### 3. Line Balancing
* **Filament Label:** `Line Balancing`
* **Description:** Compares real-time work cell cycles to target demand speed to isolate process bottlenecks.
* **Key Database Fields:** `takt_time_seconds`, `cell_cycle_time_seconds`, `idle_time_variance_seconds`, `is_bottleneck`.

---

## 🎯 Group 3: Quality Assurance
* **Filament Group Property:** `protected static ?string $navigationGroup = 'Quality Assurance';`
* **Purpose:** Manages automated statistical checks, scrap metrics, and corrective workflows.

### 1. Control Parameters
* **Filament Label:** `SPC Parameters`
* **Description:** Holds Critical-to-Quality dimensions alongside structural Upper and Lower Statistical Control Limits.
* **Key Database Fields:** `parameter_name`, `target_metric`, `upper_control_limit`, `lower_control_limit`.

### 2. Defect Logs
* **Filament Label:** `Defect Registries`
* **Description:** Records production floor quality failures, material scraps, and required product rework pathways.
* **Key Database Fields:** `work_order_id`, `defect_category`, `scrap_count`, `calculated_rework_cost`.

### 3. CAPA Tracker
* **Filament Label:** `Corrective Actions`
* **Description:** Governs formal engineering resolution cycles powered by standard "5 Whys" root-cause documentation.
* **Key Database Fields:** `root_cause_summary`, `containment_deadline`, `verification_date`, `assigned_action_owner_id`.

---

## 🚚 Group 4: Material Flow
* **Filament Group Property:** `protected static ?string $navigationGroup = 'Material Flow';`
* **Purpose:** Coordinates inventory velocities and handles lean Just-In-Time replenishment tasks.

### 1. Bill of Materials
* **Filament Label:** `BOM Structural Trees`
* **Description:** Tracks nested sub-assemblies and base components required to manufacture a finished product variant.
* **Key Database Fields:** `parent_sku`, `component_sku`, `quantity_required`, `scrap_allowance_multiplier`.

### 2. WIP Tracking
* **Filament Label:** `Work-in-Progress`
* **Description:** Monitors precise quantities of materials resting inside buffer lanes between operations.
* **Key Database Fields:** `current_routing_step_id`, `queue_quantity`, `duration_in_queue_minutes`, `last_moved_at`.

### 3. Kanban Triggers
* **Filament Label:** `Material Pull Cards`
* **Description:** Automated demand-pull mechanisms prompting material handlers to restock depleted workstations.
* **Key Database Fields:** `card_id`, `reorder_point_level`, `safety_stock_quantity`, `supplier_lead_time_days`.

---

## 🦺 Group 5: Ergonomics & Safety
* **Filament Group Property:** `protected static ?string $navigationGroup = 'Ergonomics & Safety';`
* **Purpose:** Prioritizes human factor variables to maintain workplace compliance and long-term biological safety.

### 1. Ergonomic Assessments
* **Filament Label:** `Postural Risk Audits`
* **Description:** Evaluates physical workplace ergonomics using standard REBA or RULA posture matrices.
* **Key Database Fields:** `work_cell_id`, `reba_score`, `risk_tier_classification`, `recommended_ergonomic_mitigation`.

### 2. Environmental Audits
* **Filament Label:** `Station Environment Logs`
* **Description:** Audits the ambient physical properties that directly govern plant operator endurance and error rates.
* **Key Database Fields:** `noise_decibels`, `lighting_lux`, `ambient_temperature_celsius`, `air_quality_index`.

### 3. Near-Miss & Injury Logs
* **Filament Label:** `Safety Incident Records`
* **Description:** Maps structural floor safety hazards using anatomical damage reports and hazard mapping.
* **Key Database Fields:** `incident_location_id`, `affected_body_zone`, `identified_ergonomic_hazard`, `remedial_action_taken`.

---

## 🛠️ Group 6: Workforce & Skills
* **Filament Group Property:** `protected static ?string $navigationGroup = 'Workforce & Skills';`
* **Purpose:** Pairs work cell tasks to individual operator profiles to enforce safety and speed compliance.

### 1. Skill Matrix
* **Filament Label:** `Operator Competencies`
* **Description:** Authorizes shop-floor operators to use specialized machinery based on training records.
* **Key Database Fields:** `operator_user_id`, `equipment_type_id`, `competency_level` (Trainee, Qualified, Trainer), `expiry_date`.
