# AI Macro Copilot Drawer Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Build a page-aware, institutional AI Macro Copilot drawer accessible across all views via a floating button or `Ctrl+K` hotkey, connected to Google AI Studio (Gemini 3.1 Flash) with an automated deterministic fallback engine.

**Architecture:** A lightweight Alpine.js slide-over drawer communicates via AJAX with `POST /api/copilot/ask`. The backend `MacroCopilotService` evaluates the prompt along with the user's active page path (`/scanner`, `/portfolio`, `/backtest`, etc.), querying Google Gemini API or seamlessly using the deterministic macro transmission intelligence engine.

**Tech Stack:** Laravel 12, PHP 8.5, Tailwind CSS v4, Alpine.js, Google Gemini API (Google AI Studio), PHPUnit 12.

**Spec:** [docs/superpowers/specs/2026-09-12-macro-copilot-drawer-design.md](file:///mnt/e/HackhathonProject/docs/superpowers/specs/2026-09-12-macro-copilot-drawer-design.md)

## Global Constraints
- Laravel Boost guidelines: Run `vendor/bin/pint --dirty --format agent` before committing.
- Do not break or regress existing tests (all 35 existing tests must stay passing).
- Zero third-party JS libraries; use Alpine.js already bundled in the app.
- Resilience: If `GEMINI_API_KEY` is empty or the remote API fails/times out, the deterministic engine must respond instantaneously with rich Indonesian macro analysis and actionable takeaways.
- NTFS/WSL git lock: Safely remove `.git/index.lock` before git commits using the python retry loop.

---

### Task 1: Scaffolding `MacroCopilotService` & Feature Tests

**Files:**
- Create: `app/Services/MacroCopilotService.php`
- Test: `tests/Feature/MacroCopilotTest.php`

**Interfaces:**
- Produces:
  - `MacroCopilotService::ask(string $message, ?string $page = null, array $history = []): array{ answer: string, engine: string, suggested_followups: array<string> }`

- [ ] **Step 1: Write failing feature tests** in `tests/Feature/MacroCopilotTest.php` testing validation and service response structure.
- [ ] **Step 2: Run test** to verify it fails (`php artisan test --filter=MacroCopilotTest`).
- [ ] **Step 3: Implement `MacroCopilotService`** with Gemini API call, contextual prompts for each page, and high-fidelity deterministic fallback.
- [ ] **Step 4: Run test** to verify it passes.
- [ ] **Step 5: Run Pint** (`vendor/bin/pint --dirty --format agent`).
- [ ] **Step 6: Commit** changes with python retry script (`feat(copilot): implement MacroCopilotService with Google Gemini and deterministic fallback`).

---

### Task 2: Scaffolding `MacroCopilotController` & API Route

**Files:**
- Create: `app/Http/Controllers/MacroCopilotController.php`
- Modify: `routes/web.php`
- Test: `tests/Feature/MacroCopilotTest.php`

**Interfaces:**
- Consumes: `MacroCopilotService::ask`
- Produces: Route `POST /api/copilot/ask` (`copilot.ask`) returning JSON response.

- [ ] **Step 1: Add endpoint test cases** in `tests/Feature/MacroCopilotTest.php` (`test_copilot_ask_endpoint_validates_required_message`, `test_copilot_ask_endpoint_returns_successful_json_response`, `test_copilot_handles_page_context`).
- [ ] **Step 2: Run test** to verify endpoint tests fail.
- [ ] **Step 3: Implement `MacroCopilotController`** and register route in `routes/web.php`.
- [ ] **Step 4: Run test** to verify all tests pass.
- [ ] **Step 5: Run Pint** (`vendor/bin/pint --dirty --format agent`).
- [ ] **Step 6: Commit** changes with python retry script (`feat(copilot): create MacroCopilotController and /api/copilot/ask route`).

---

### Task 3: Building the Institutional Alpine.js Drawer Component

**Files:**
- Create: `resources/views/components/macro-copilot.blade.php`

**Interfaces:**
- Produces: Blade component `<x-macro-copilot />` providing:
  - Floating pill button at `bottom-6 right-6` with `Ctrl+K` indicator.
  - Global `Ctrl+K` / `Cmd+K` keyboard event listener.
  - Slide-over drawer with dark institutional styling.
  - Quick prompt chips (1-click questions).
  - Conversation history display with Markdown-style bold/bullets.
  - Contextual awareness badge (`📍 Konteks: ...`).
  - Textarea input with loading state and send action.

- [ ] **Step 1: Create `resources/views/components/macro-copilot.blade.php`** using Alpine.js and Tailwind CSS v4 styling.
- [ ] **Step 2: Implement AJAX fetch logic** calling `route('copilot.ask')` with CSRF token and current `window.location.pathname`.
- [ ] **Step 3: Add unit/render test** in `tests/Feature/MacroCopilotTest.php` to verify component renders correctly.
- [ ] **Step 4: Run test** to ensure it passes.
- [ ] **Step 5: Run Pint** (`vendor/bin/pint --dirty --format agent`).
- [ ] **Step 6: Commit** changes (`feat(copilot): create interactive Alpine.js macro copilot drawer component`).

---

### Task 4: Global Layout Integration & Final Verification

**Files:**
- Modify: `resources/views/dashboard.blade.php`
- Modify: `resources/views/scanner.blade.php`
- Modify: `resources/views/portfolio.blade.php`
- Modify: `resources/views/duel.blade.php`
- Modify: `resources/views/backtest.blade.php`
- Modify: `resources/views/macro-report.blade.php`
- Test: `tests/Feature/MacroCopilotTest.php`

- [ ] **Step 1: Include `<x-macro-copilot />`** in all 6 core views.
- [ ] **Step 2: Add integration tests** in `tests/Feature/MacroCopilotTest.php` verifying the copilot component is present in all primary views.
- [ ] **Step 3: Rebuild frontend assets** (`npm run build`).
- [ ] **Step 4: Run test suite** (`php artisan test --filter=MacroCopilotTest`).
- [ ] **Step 5: Run Pint** (`vendor/bin/pint --dirty --format agent`).
- [ ] **Step 6: Commit** changes (`feat(copilot): integrate Macro Copilot into all 6 core application views`).
