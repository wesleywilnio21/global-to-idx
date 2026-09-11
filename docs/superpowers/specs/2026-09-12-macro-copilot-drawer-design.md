# AI Macro Copilot Drawer Design Specification

## Overview
MacroSectors AI features an institutional-grade, page-aware AI Assistant called **Macro Copilot**. Accessible across all application pages via a floating trigger button or global keyboard shortcut (`Ctrl + K` / `Cmd + K`), it provides instantaneous macro-sectoral analysis, transmission reasoning, and strategic investment insights powered by **Google Gemini (Google AI Studio)** with an automated **High-Fidelity Deterministic Fallback Engine**.

---

## 1. User Experience & UI Component

### 1.1 Floating Trigger & Hotkey
- **Floating Button**: Positioned fixed at `bottom-6 right-6 z-40`. Styled with an institutional dark slate pill, subtle indigo glow/ring, AI sparkles icon (`✨`), label *"Tanya Macro Copilot"*, and keyboard shortcut badge `<kbd>Ctrl+K</kbd>`.
- **Keyboard Shortcut**: Global event listener for `Ctrl + K` and `Meta + K` (Mac) to toggle open/close. `Esc` key closes the drawer.

### 1.2 Slide-Over Drawer Panel
- Positioned fixed at `top-0 right-0 h-full w-full max-w-lg z-50` with backdrop overlay blur (`backdrop-blur-sm bg-slate-950/60`).
- Header:
  - Title: **Macro Copilot AI** with institutional badge `Gemini 3.1 Flash`.
  - Current Page Context pill (e.g., `📍 Konteks: Solvency Scanner (/scanner)`).
  - Close button (`✕`) and Clear Chat history button (`🗑️`).
- Body:
  - Welcome banner with institutional guidance.
  - **Quick Prompt Chips** (clickable pills that immediately send prompt):
    - 💡 *"Sektor apa paling kebal jika USD tembus Rp16.800?"*
    - 🛡️ *"Kenapa BBCA lebih defensif dari BBRI saat suku bunga naik?"*
    - 🚨 *"Emiten mana yang terancam Red-Line hari ini?"*
    - 🔄 *"Strategi rotasi sektor terbaik semester ini?"*
  - Scrollable chat message timeline:
    - User message bubble (slate/blue, right-aligned).
    - Copilot message bubble (dark card with emerald AI badge, left-aligned, rendered with Markdown bold/bullet points, and source engine tag).
    - Loading state: Animated typing dots / pulse indicator.
- Footer:
  - Auto-expanding textarea input with placeholder *"Tanyakan transmisi makro, emiten, atau sektor..."*.
  - Send button (`Enter` to submit, `Shift+Enter` for newline).
  - Disclaimer footer: *"Didukung Google Gemini & Model Transmisi Deterministik BEI"*.

---

## 2. Page-Aware Context Injection

When sending a query to `/api/copilot/ask`, the frontend includes the active page context:
- `page`: Current window pathname (e.g., `/`, `/portfolio`, `/scanner`, `/backtest`, `/duel`, `/tear-sheet`).
- Contextual data available on the page (e.g., active scenario ID, ticker symbols, crisis key).

The backend formats this into a tailored system prompt so that the Copilot knows:
- **On `/scanner`**: Aware of the 49 constituent companies, DER thresholds, and Red-Line breached status.
- **On `/portfolio`**: Aware of user portfolio asset weights and beta-adjusted shock metrics.
- **On `/backtest`**: Aware of the selected crisis (Covid 2020, Taper Tantrum 2013, Trade War 2018) and empirical validation accuracy.
- **On `/duel`**: Aware of the two compared stocks/sectors and sensitivity radar metrics.
- **On `/dashboard`**: Aware of the active macro scenario and sectoral transmission winners/losers.

---

## 3. Backend Architecture & Engine

### 3.1 Endpoint
- `POST /api/copilot/ask`
- Request Payload:
  ```json
  {
    "message": "Bagaimana dampak kurs USD naik ke sektor farmasi dan konsumen?",
    "page": "/dashboard",
    "history": [
      { "role": "user", "content": "..." },
      { "role": "assistant", "content": "..." }
    ]
  }
  ```
- Response Payload:
  ```json
  {
    "answer": "Pelemahan Rupiah terhadap USD memberikan dampak ganda...",
    "engine": "Google Gemini (gemini-3.1-flash-lite)",
    "suggested_followups": [
      "Bagaimana posisi utang valas KLBF vs KAEF?",
      "Apakah sektor energi diuntungkan dari pelemahan kurs ini?"
    ]
  }
  ```

### 3.2 Dual-Engine Strategy
1. **Google Gemini (AI Studio)**:
   - Uses `GEMINI_API_KEY` from `config('sectors.ai.gemini_api_key')`.
   - Calls Google Generative Language API endpoint (`models/{gemini-model}:generateContent`).
   - Temperature: `0.3` (precise, analytical, institutional financial tone).
2. **High-Fidelity Deterministic Fallback**:
   - If API key is not present, invalid, or rate-limited:
   - Evaluates user query keywords against sectoral transmission matrix, company metrics (DER, margin, foreign debt), and Indonesian macroeconomic principles.
   - Generates high-quality, structured analytical responses with specific stock tickers and percentage sensitivities.
   - Guaranteed 0% failure rate during live hackathon judging.

---

## 4. Quality & Testing Plan
- **TDD Test Suite**: `tests/Feature/MacroCopilotTest.php`
  - Validates `POST /api/copilot/ask` validation rules (message required).
  - Validates successful response structure (`answer`, `engine`, `suggested_followups`).
  - Validates deterministic fallback when Gemini API is offline.
  - Validates page context handling.
  - Validates UI component rendering across views.
- **Linting**: Run `vendor/bin/pint --dirty --format agent`.
- **Frontend Assets**: Rebuilt using `npm run build`.
