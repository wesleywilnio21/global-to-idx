# Historical Shock Backtest Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Membangun modul "Kilas Balik Krisis Historis" (Historical Shock Backtest) pada rute `/backtest` untuk memvalidasi akurasi model AI MacroSectors terhadap data empiris 3 krisis nyata Indonesia (Taper Tantrum 2013, Trade War 2018, dan Covid Crash 2020).

**Architecture:** Modul menyajikan dataset empiris 11 sektor BEI saat krisis, membandingkannya dengan skor prediksi model, menghitung metrik *Directional Accuracy*, menyajikan matriks perbandingan visual side-by-side, serta narasi pembelajaran retrospektif. Terintegrasi ke sidebar dan diuji dengan PHPUnit Feature Tests.

**Tech Stack:** Laravel 12 / PHP 8.5, Tailwind CSS, Alpine.js, PHPUnit Feature Testing.

**Spec:** `docs/superpowers/specs/2026-09-11-historical-backtest-design.md`

## Global Constraints
- Mengikuti pedoman Laravel Boost & Pint: `vendor/bin/pint --dirty --format agent`.
- Seluruh 24 tes yang ada saat ini harus tetap passing tanpa regresi.
- UI bergaya institusional (clean, spacious, Geist Mono tabular numbers, no cramped elements).
- Hindari hard reload jika memungkinkan, dukung navigasi mulus.

---

### Task 1: Scaffolding Route & Feature Test

**Files:**
- Create: `tests/Feature/HistoricalBacktestTest.php`
- Modify: `routes/web.php`
- Create: `app/Http/Controllers/HistoricalBacktestController.php`

**Interfaces:**
- Produces: Route `GET /backtest` (`backtest.index`) -> `HistoricalBacktestController@index`

- [ ] **Step 1: Tulis feature test awal**
Buat `tests/Feature/HistoricalBacktestTest.php`:
```php
<?php

namespace Tests\Feature;

use Tests\TestCase;

class HistoricalBacktestTest extends TestCase
{
    public function test_backtest_page_renders_successfully(): void
    {
        $response = $this->get('/backtest');

        $response->assertStatus(200);
        $response->assertSee('Kilas Balik Krisis');
        $response->assertSee('Covid-19');
    }

    public function test_can_switch_crisis_scenario(): void
    {
        $response = $this->get('/backtest?crisis=taper_tantrum_2013');
        $response->assertStatus(200);
        $response->assertSee('Taper Tantrum');

        $response2 = $this->get('/backtest?crisis=trade_war_2018');
        $response2->assertStatus(200);
        $response2->assertSee('Perang Dagang');
    }
}
```

- [ ] **Step 2: Jalankan test untuk memverifikasi kegagalan**
Run: `php artisan test --filter=HistoricalBacktestTest`
Expected: FAIL (404 atau route not found)

- [ ] **Step 3: Buat Controller dan Route**
Tambahkan di `routes/web.php`:
```php
use App\Http\Controllers\HistoricalBacktestController;

Route::get('/backtest', [HistoricalBacktestController::class, 'index'])->name('backtest.index');
```

Buat `app/Http/Controllers/HistoricalBacktestController.php`:
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class HistoricalBacktestController extends Controller
{
    public function index(Request $request): View
    {
        return view('backtest');
    }
}
```

Buat view placeholder minimal `resources/views/backtest.blade.php`:
```html
<div>Kilas Balik Krisis - Covid-19 - Taper Tantrum - Perang Dagang</div>
```

- [ ] **Step 4: Jalankan test untuk memastikan status 200**
Run: `php artisan test --filter=HistoricalBacktestTest`
Expected: PASS

- [ ] **Step 5: Commit**
```bash
git add tests/Feature/HistoricalBacktestTest.php routes/web.php app/Http/Controllers/HistoricalBacktestController.php resources/views/backtest.blade.php
git commit -m "feat(backtest): scaffold route and feature test for historical shock backtest"
```

---

### Task 2: Historical Dataset & Empirical Validation Engine

**Files:**
- Modify: `app/Http/Controllers/HistoricalBacktestController.php`
- Modify: `tests/Feature/HistoricalBacktestTest.php`

**Interfaces:**
- Produces: `$crises`, `$selectedCrisisKey`, `$crisisData`, `$directionalAccuracy`, `$topDefensive`, `$topVulnerable` passed to `backtest.blade.php`.

- [ ] **Step 1: Tambahkan assertion dataset di test**
Update `tests/Feature/HistoricalBacktestTest.php` untuk memverifikasi kalkulasi akurasi arah dan kelengkapan 11 sektor:
```php
    public function test_crisis_dataset_contains_all_11_sectors_and_accuracy(): void
    {
        $response = $this->get('/backtest?crisis=covid_crash_2020');
        $response->assertStatus(200);
        $response->assertViewHas('directionalAccuracy');
        $response->assertViewHas('sectorComparison');
        
        $sectors = $response->viewData('sectorComparison');
        $this->assertCount(11, $sectors);
        
        $accuracy = $response->viewData('directionalAccuracy');
        $this->assertGreaterThanOrEqual(80, $accuracy);
    }
```

- [ ] **Step 2: Jalankan test untuk melihat kegagalan**
Run: `php artisan test --filter=HistoricalBacktestTest`
Expected: FAIL (viewData missing)

- [ ] **Step 3: Implementasikan dataset dan logika kalkulasi di Controller**
Di `HistoricalBacktestController.php`, susun 3 dataset historis riil (Taper Tantrum 2013, Trade War 2018, Covid Crash 2020) dengan parameter makro, real drawdown 11 sektor, skor prediksi model, directional alignment, dan commentary.
Hitung `directionalAccuracy` (persentase sektor yang arah prediksinya tepat).

- [ ] **Step 4: Jalankan test**
Run: `php artisan test --filter=HistoricalBacktestTest`
Expected: PASS

- [ ] **Step 5: Format & Commit**
```bash
vendor/bin/pint --dirty --format agent
git add app/Http/Controllers/HistoricalBacktestController.php tests/Feature/HistoricalBacktestTest.php
git commit -m "feat(backtest): implement empirical crisis datasets and directional accuracy engine"
```

---

### Task 3: Institutional UI View & Comparative Visuals

**Files:**
- Modify: `resources/views/backtest.blade.php`
- Modify: `tests/Feature/HistoricalBacktestTest.php`

**Interfaces:**
- Produces: Full institutional UI matching the styling of `portfolio` and `scanner`.

- [ ] **Step 1: Rancang layout lengkap `resources/views/backtest.blade.php`**
Bangun view mencakup:
1. Header institusional dengan badge `EMPIRICAL MODEL VALIDATION` dan tombol Cetak PDF.
2. 3 Tombol Tab / Kartu Skenario Krisis Interaktif (`Taper Tantrum 2013`, `Trade War 2018`, `Covid-19 Crash 2020`) dengan penanda visual aktif.
3. Macro Context Deck: 4 metrik makro saat krisis (Kurs USD/IDR, BI-Rate Shock, Inflasi/PDB, IHSG Drawdown).
4. Model Validation Scorecard: 3 KPI Cards (Akurasi Arah Prediksi %, Top Sektor Defensif, Top Sektor Rentan).
5. Matriks Komparasi 11 Sektor:
   - Bar side-by-side Realita Historis vs Prediksi Model.
   - Badge status presisi: `Bullseye`, `Konsisten`, `Divergen Minor`.
   - Driver ekonomi utama sektor tersebut.
6. Retrospective AI Post-Mortem Card: Pelajaran berharga bagi investor masa kini.

- [ ] **Step 2: Tambahkan UI assertions di feature test**
Verifikasi di `HistoricalBacktestTest.php` bahwa elemen matriks komparasi dan scorecard ter-render di HTML.

- [ ] **Step 3: Jalankan test & compile assets**
Run:
```bash
php artisan test --filter=HistoricalBacktestTest
npm run build
```
Expected: PASS & Vite build sukses.

- [ ] **Step 4: Commit**
```bash
git add resources/views/backtest.blade.php tests/Feature/HistoricalBacktestTest.php
git commit -m "feat(backtest): design institutional comparative UI and retrospective post-mortem cards"
```

---

### Task 4: Sidebar Navigation & Final Verification

**Files:**
- Modify: `resources/views/layouts/sidebar.blade.php`
- Modify: `tests/Feature/HistoricalBacktestTest.php`

**Interfaces:**
- Produces: Sidebar link pointing to `route('backtest.index')` with `HISTORIS` badge.

- [ ] **Step 1: Tambahkan test link sidebar**
Di `HistoricalBacktestTest.php`:
```php
    public function test_sidebar_contains_link_to_backtest(): void
    {
        $response = $this->get('/backtest');
        $response->assertSee(route('backtest.index'));
        $response->assertSee('Kilas Balik Krisis');
    }
```

- [ ] **Step 2: Update `resources/views/layouts/sidebar.blade.php`**
Tambahkan item menu ke-6:
```html
<!-- 6. Kilas Balik Krisis (Historical Backtest) -->
<a href="{{ route('backtest.index') }}" 
   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('backtest.*') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
    <svg class="w-4 h-4 {{ request()->routeIs('backtest.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
    </svg>
    <span>Kilas Balik Krisis</span>
    <span class="ml-auto text-[10px] px-1.5 py-0.5 rounded font-bold uppercase {{ request()->routeIs('backtest.*') ? 'bg-blue-500 text-white' : 'bg-indigo-50 text-indigo-700 border border-indigo-200' }}">Histori</span>
</a>
```

- [ ] **Step 3: Jalankan seluruh test suite aplikasi**
Run: `php artisan test`
Expected: 25+ tests pass (0 failure).

- [ ] **Step 4: Format kode & Build frontend**
Run:
```bash
vendor/bin/pint --dirty --format agent
npm run build
php artisan view:clear
```

- [ ] **Step 5: Commit final**
```bash
git add resources/views/layouts/sidebar.blade.php tests/Feature/HistoricalBacktestTest.php
git commit -m "feat(backtest): integrate backtest module into sidebar navigation and verify test suite"
```
