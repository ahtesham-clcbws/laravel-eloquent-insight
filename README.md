# Laravel Eloquent Insight 🧠

[![Latest Version on GitHub](https://img.shields.io/github/v/release/ahtesham-clcbws/laravel-eloquent-insight?include_prereleases&style=flat-square)](https://github.com/ahtesham-clcbws/laravel-eloquent-insight/releases)
[![Total Downloads](https://img.shields.io/packagist/dt/clcbws/laravel-eloquent-insight.svg?style=flat-square)](https://packagist.org/packages/clcbws/laravel-eloquent-insight)
[![License](https://img.shields.io/github/license/ahtesham-clcbws/laravel-eloquent-insight?style=flat-square)](https://github.com/ahtesham-clcbws/laravel-eloquent-insight/blob/main/LICENSE)

**Laravel Eloquent Insight** is a premium performance suite that identifies, ranks, and resolves database bottlenecks. While most tools just "shout" at you when an N+1 violation happens, Insight provides the **intelligence to understand why** and the **tools to fix it automatically**.

Fully optimized for **Laravel 13.x**, with legacy support for 12.x and 11.x.

---

## ❓ Why Eloquent Insight?

In standard Laravel development, the **N+1 Problem** is the #1 killer of application performance. You load 50 users, and Laravel accidentally fires 51 queries to fetch their profiles.

**The Problem with Other Tools:**
- 🐢 **Runtime Overhead**: They slow down your production app while "watching" for errors.
- 📣 **Noise**: They give you a flat list of errors without telling you which ones actually hurt your performance.
- 🔧 **Manual Labor**: You still have to manually find the code and fix it.

**The Insight Solution:**
- ⚡ **Zero Production Overhead**: Recording only happens in dev; production uses a static O(1) manifest.
- 🏆 **Impact Scoring**: We tell you which bottlenecks are **CRITICAL** (e.g., 100+ redundant queries) vs. moderate.
- 🧙 **Auto-Refactoring**: Our AST-engine can actually **write the fix into your code** for you.

---

## ✨ Key Features (The Elite Suite)

### 📊 Impact Scoring
Not all N+1s are equal. Insight ranks every violation:
- 🔴 **CRITICAL**: Generating 50+ redundant queries. Fix immediately!
- 🟠 **HIGH**: Generating 10-50 queries. Significant slowdown.
- 🔵 **MODERATE**: Low-level redundancy.

### 👻 Ghost Relation Detection
Sometimes you use `->with(['user', 'profile'])` but then delete the code that used the profile. The `profile` is still being loaded, wasting memory and DB time. Insight flags these "Ghost Relations" so you can strip them out.

### 💧 Hydration Profiling (The "Fat Model" Finder)
If you `SELECT *` on a table with 50 columns but only display the `name`, you are wasting memory. Insight compares what you **fetched** vs. what you **accessed**, suggesting surgical `->select([...])` arrays.

### 🗺️ Query Topology Visualization
Complex apps have messy relationship chains. Insight generates a **Mermaid.js** graph that shows exactly how your queries flow, helping you visualize the "execution distance" between your models.

### 🔄 Redundancy (Duplicate) Detection
Detects identical SQL queries with matching bindings fired in the same request—often a sign of redundant service calls or missing caching logic.

---

## 🚀 The "Capture-Compile-Resolve" Workflow

Insight follows a professional 3-step lifecycle:

1. **CAPTURE (Local)**: While you develop, Insight records violations in the background. You can see them in your terminal or via the **Browser UI Overlay**.
2. **COMPILE (CI/Build)**: Before deploying, you run `php artisan insight:compile`. This takes all your developer "fixes" and squashes them into a high-speed PHP manifest.
3. **RESOLVE (Production)**: In production, our `RuntimeResolver` reads the manifest and "magically" eager-loads the missing relations with **zero database tracing**.

---

## 📦 Installation

```bash
composer require clcbws/laravel-eloquent-insight --dev
```

*Note: The resolution engine is production-safe, but the recording tools are strictly for development.*

---

## 🛠️ Usage (CLI & Terminal)

### 3. Clear the Slate
If you want to start a fresh audit session, clear the historical logs:
```bash
php artisan insight:clear
```

### 4. Audit & Fix
Once violations are captured, run the analysis:
```bash
# List all captured N+1 violations
php artisan insight:list

# Get a high-level efficiency audit (Hydration, Duplicates, Ghost Relations)
php artisan insight:audit

# Automatically fix N+1 violations
php artisan insight:fix --all
```

### 🚀 Production Deployment
For maximum performance in production, compile your detected optimizations into a static manifest. This converts dynamic analysis into an O(1) lookup:
```bash
php artisan insight:compile
```
This will generate `storage/framework/insight/manifest.json`, which is then used by the runtime engine with zero overhead.

### Smart Auto-Fixer (The Wizard)
To have Insight automatically refactor your source code to add missing `with()` calls:
```bash
php artisan insight:fix --all
```

---

### 🖥️ UI Integration (Controllers, Livewire, Blade)

#### 🎨 Browser UI Overlay
In your local environment, a premium Shadow-DOM overlay is injected into your HTML responses.
- **Real-time Alerts**: An "Insight" button appears in the bottom-right corner when issues are detected.
- **Visual Breakdown**: View N+1 violations, Ghost relations, and Duplicate queries without leaving your browser.
- **One-Click Fixes**: Apply AST-based source code refactoring directly from the overlay.

#### 🎮 Controller Usage
Perfect for building custom admin APIs or JSON health endpoints.

```php
use EloquentInsight\Facades\Insight;

public function checkEfficiency()
{
    $solutions = Insight::getLatestSolutions();
    $efficiency = Insight::getEfficiencyReport();

    return response()->json([
        'score' => Insight::getPerformanceScore(),
        'bottlenecks' => $solutions,
        'ghost_relations' => $efficiency['ghost_relations'],
        'duplicates' => Insight::getDuplicateReport(),
    ]);
}
```

#### ⚡ Livewire Integration
Build a real-time "Eloquent Health" indicator for your admin panel.

```php
namespace App\Livewire;

use Livewire\Component;
use EloquentInsight\Facades\Insight;

class PerformanceMonitor extends Component
{
    public function render()
    {
        return view('livewire.performance-monitor', [
            'solutions' => Insight::getLatestSolutions(),
            'score' => Insight::getPerformanceScore(),
            'efficiency' => Insight::getEfficiencyReport(),
        ]);
    }
}
```

#### 🍃 Blade Templates
Quickly show an alert to administrators if performance regressions are detected in local dev.

```blade
@if(app()->isLocal() && Insight::getPerformanceScore() < 90)
    <div class="alert alert-warning">
        <strong>🧠 Insight Notice:</strong> Your database efficiency has dropped to {{ Insight::getPerformanceScore() }}%. 
        Run <code>php artisan insight:audit</code> to review.
    </div>
@endif
```

---

## 🏗️ Architecture

1. **Collector**: Low-level event listeners for Eloquent events.
2. **Heuristic Engine**: Pattern-matching logic to identify entry points.
3. **AST Fixer**: Safe PHP code modification via Abstract Syntax Trees.
4. **O(1) Resolver**: High-speed manifest loader for production.

---

## 🚀 Roadmap

Future versions of **Eloquent Insight** will focus on expanding the auto-fix capabilities:

- [ ] **Automated Hydration Optimization**: Automatically inject `->select()` calls based on detected attribute access patterns.
- [ ] **Duplicate Query Refactoring**: Identify redundant logic blocks and suggest caching or result-reuse strategies.
- [ ] **Custom Heuristic Rules**: Allow developers to define their own performance "red lines."
- [ ] **In-Browser IDE Links**: Click a violation in the audit report to jump directly to the line in VS Code/PhpStorm.

---

## 📊 Comparison: Why Insight Wins

| Feature | Standard Trackers (DebugBar/Clockwork) | Laravel Eloquent Insight |
| :--- | :--- | :--- |
| **Production Overhead** | 🐢 High (Continuous Tracing) | ⚡ **Zero** (Static Resolver) |
| **Resolution** | 📣 Manual Fixing Only | 🧙 **Auto-Fix (AST Engine)** |
| **Analysis** | 📄 Flat List of Queries | 🏆 **Impact Scoring (Weighted)** |
| **Optimization** | ❌ None | 📦 **O(1) Compiled Manifest** |
| **Visuals** | 📊 Generic Tables | 🗺️ **Mermaid Topology Graphs** |

---

## ⚙️ Advanced Configuration

Beyond the basic toggles, you can fine-tune the engine in `config/insight.php`:

### `ignore_namespaces`
Prevent Insight from analyzing internal framework traces. This keeps your reports focused on **your** code.
```php
'ignore_namespaces' => [
    'Illuminate\\',
    'EloquentInsight\\',
    'Sentry\\', // Ignore third-party packages
],
```

### `storage_path`
Custom location for the violation buffer and exported reports.
```php
'storage_path' => storage_path('framework/insight'),
```

---

## 🛡️ Production Security & Gating

We take production stability seriously. **Eloquent Insight** uses a dual-layer gate:
1. **Physical Gating**: The `ViolationInterceptor` and `EfficiencyInterceptor` strictly check `\app()->isLocal()` before registering any listeners.
2. **Feature Gating**: All recording logic is disabled if `INSIGHT_ENABLED` is false in your `.env`.

In production, only the **Runtime Resolver** is active, performing a single O(1) array lookup per request. **No traces are generated, and no files are written.**

---

## 🛠️ Troubleshooting

### "The UI Overlay isn't appearing"
1. Ensure `INSIGHT_UI_ENABLED=true` is set in your `.env`.
2. Verify you are in the `local` environment (`APP_ENV=local`).
3. Insight only injects into standard HTML responses (it skips JSON/API calls).

### "Permission denied in storage"
Insight needs to write to `storage/framework/insight/`. Ensure this directory is writable by your web server:
```bash
mkdir -p storage/framework/insight/reports
chmod -R 775 storage/framework/insight
```

---

## 🤝 Credits

- **Author**: [Ahtesham](mailto:ahtesham@clcbws.com)
- **Company**: [Broadway Web Service](https://www.clcbws.com)

---
