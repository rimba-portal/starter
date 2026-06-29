# Filament PHP Architecture & Layout Hierarchy Cheatsheet

A quick reference guide tracking the nested Execution Tree of Filament Panels (v3). It maps background PHP logic controllers to core frontend Blade template components.

---

## 1. Main Dashboard Pages (Resource Lists / Tables)

Used when rendering resource index dashboards (e.g., viewing a table grid, running bulk actions, or managing filters).

### Visual Execution Tree
```html
<!DOCTYPE html> <!-- base.blade.php -->
<html>
<head> <!-- Scripts, Alpine, Tailwind CSS --> </head>
<body>
    <div class="panel-layout"> <!-- layout/index.blade.php -->
        <aside class="sidebar"> <!-- sidebar/index.blade.php --> </aside>
        <main class="content-area">
            <header class="topbar"> <!-- topbar/index.blade.php --> </header>
            
            <!-- Livewire Component Boundary -->
            <div class="livewire-page"> <!-- list-records.blade.php -->
                <header class="page-header"> <!-- Header Actions --> </header>
                
                <div class="tables-container"> <!-- tables/index.blade.php -->
                    <!-- Atomic Columns / Rows -->
                    <span class="text-column"> <!-- text-column.blade.php --> </span>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
```

### Component Architecture Mapping

| Layer / Structural Component | Backend PHP Logic File | Frontend Blade Template File |
| :--- | :--- | :--- |
| **1. Global Document Shell** | *Handled by middleware pipelines* | `vendor/filament/support/resources/views/components/layout/base.blade.php` |
| **2. Admin Panel Layout Frame** | *Assembled via Panel Provider config* | `vendor/filament/filament/resources/views/components/layout/index.blade.php` |
| **3. Top Navigation Bar** | `Filament\View\Components\Topbar` | `vendor/filament/filament/resources/views/components/topbar/index.blade.php` |
| **4. Sidebar Navigation Panel**| `Filament\View\Components\Sidebar` | `vendor/filament/filament/resources/views/components/sidebar/index.blade.php` |
| **5. Livewire Application Page**| `app/Filament/Resources/[Model]Resource/Pages/List[Models].php` | `vendor/filament/filament/resources/views/resources/pages/list-records.blade.php` |
| **6. The Livewire Datatable Wrapper** | `Filament\Tables\Table` | `vendor/filament/tables/resources/views/index.blade.php` |
| **7. Atomic UI Formats & Cells** | `Filament\Tables\Columns\TextColumn` | `vendor/filament/tables/resources/views/columns/text-column.blade.php` |

---

## 2. Record Management Pages (Create, Edit & View)

Used when modifying or analyzing a single Eloquent database instance. Active fields map properties via JavaScript/Livewire straight into a running local `$data` property state array pool on the backend.

### Visual Execution Tree
```html
<!DOCTYPE html> <!-- base.blade.php -->
<html>
<body>
    <div class="panel-layout"> <!-- layout/index.blade.php -->
        <main class="content-area">
            
            <!-- Livewire Component Boundary -->
            <div class="livewire-page"> <!-- [create | edit | view]-record.blade.php -->
                <header class="page-header"> 
                    <!-- Top Right Actions (e.g., Delete, View Public Site, Cancel) -->
                </header>
                
                <!-- The Form Wrapper Shell -->
                <form class="filament-form"> <!-- form/index.blade.php -->
                    
                    <!-- Layout containers managed by Form / Infolist Scheme -->
                    <div class="fi-fo-component-section"> <!-- section.blade.php -->
                        
                        <!-- Atomic Fields (Editable Input or Static Text Entry) -->
                        <div class="fi-fo-text-input"> <!-- text-input.blade.php / text-entry.blade.php --> </div>
                        
                    </div>
                </form>

                <footer class="page-actions">
                    <!-- Form Submission Actions (e.g., Save Changes, Create & Create Another) -->
                </footer>

            </div>
        </main>
    </div>
</body>
</html>
```

### Component Architecture Mapping

| Page Type / Structural Component | Backend PHP Logic File Location | Frontend Blade Template File Path |
| :--- | :--- | :--- |
| **Create Record Page** | `app/Filament/Resources/[Model]Resource/Pages/Create[Model].php` | `vendor/filament/filament/resources/views/resources/pages/create-record.blade.php` |
| **Edit Record Page** | `app/Filament/Resources/[Model]Resource/Pages/Edit[Model].php` | `vendor/filament/filament/resources/views/resources/pages/edit-record.blade.php` |
| **View Record Page (Read-Only)** | `app/Filament/Resources/[Model]Resource/Pages/View[Model].php` | `vendor/filament/filament/resources/views/resources/pages/view-record.blade.php` |
| **Form Engine Container Wrapper**| `Filament\Forms\Form` | `vendor/filament/forms/resources/views/components/form/index.blade.php` |
| **Structural Section Wrapper** | `Filament\Forms\Components\Section` | `vendor/filament/forms/resources/views/components/section.blade.php` |
| **Structural Layout Tabs Grid** | `Filament\Forms\Components\Tabs` | `vendor/filament/forms/resources/views/components/tabs.blade.php` |
| **Atomic Interactive Form Input** | `Filament\Forms\Components\TextInput` | `vendor/filament/forms/resources/views/components/text-input.blade.php` |
| **Atomic View Text Element** | `Filament\Infolists\Components\TextEntry` | `vendor/filament/infolists/resources/views/components/text-entry.blade.php` |

---

## 3. Authentication Pages (Login / Registration)

Used on standalone workspace entry pages. Bypasses navigation sidebars to center a layout profile container card.

### Visual Execution Tree
```html
<!DOCTYPE html> <!-- base.blade.php -->
<html>
<head> <!-- Meta framework --> </head>
<body>
    <div class="fi-simple-layout"> <!-- layout/simple.blade.php -->
        <div class="fi-simple-card"> <!-- card.blade.php -->
            
            <!-- Livewire Authentication Boundary -->
            <div class="livewire-auth-page"> <!-- login.blade.php -->
                <form class="filament-form"> <!-- form/index.blade.php -->
                    <!-- Form Input Fields Array -->
                    <input type="email" class="text-input" /> <!-- text-input.blade.php -->
                    
                    <button type="submit"> <!-- button.blade.php --> </button>
                </form>
            </div>

        </div>
    </div>
</body>
</html>
```

### Component Architecture Mapping

| Layer / Structural Component | Backend PHP Logic File | Frontend Blade Template File |
| :--- | :--- | :--- |
| **1. Global Document Shell** | *Handled by middleware pipelines* | `vendor/filament/support/resources/views/components/layout/base.blade.php` |
| **2. Simple Page Frame View** | *Bypasses layout sidebars / topbars* | `vendor/filament/filament/resources/views/components/layout/simple.blade.php` |
| **3. Main Center Canvas Card** | *Utility design framework block* | `vendor/filament/support/resources/views/components/card.blade.php` |
| **4. Livewire Core Auth Processor** | `vendor/filament/filament/src/Pages/Auth/Login.php` | `vendor/filament/filament/resources/views/pages/auth/login.blade.php` |
| **5. Core Form Manager Array Grid** | `Filament\Forms\Form` | `vendor/filament/forms/resources/views/components/form/index.blade.php` |
| **6. Form Engine Fields UI Input** | `Filament\Forms\Components\TextInput` | `vendor/filament/forms/resources/views/components/text-input.blade.php` |
| **7. Submission UI Execution Action** | *Triggered within Livewire directly* | `vendor/filament/support/resources/views/components/button.blade.php` |

---

## 4. Override & Extension Toolkit

### How to Override Authentication Controllers
1. Extend the core login controller class:
   ```php
   // app/Filament/Pages/Auth/CustomLogin.php
   namespace App\Filament\Pages\Auth;
   use Filament\Pages\Auth\Login as BaseLogin;

   class CustomLogin extends BaseLogin {
       // Insert custom state adjustments or overrides here
   }
   ```
2. Bind your custom file layout to your dashboard framework engine:
   ```php
   // app/Providers/Filament/AdminPanelProvider.php
   public function panel(Panel \$panel): Panel {
       return \$panel
           ->default()
           ->login(CustomLogin::class); // Bind custom class here
   }
   ```

### How to Edit Raw Core Layout Views
Publish files safely into your root project architecture workspace layout manually:

```bash
# Extract Admin Panel layout views
php artisan vendor:publish --tag=filament-panels-views

# Extract Form layout views
php artisan vendor:publish --tag=filament-forms-views

# Extract Data Table grid layout views
php artisan vendor:publish --tag=filament-tables-views

# Extract Infolist read-only layout views
php artisan vendor:publish --tag=filament-infolists-views
```
*Published layouts will appear ready for customized staging modifications under your application's `resources/views/vendor/` directory.*
