# MacroSectors AI Design System & Anti-Slop Rules

Adapted from `taste-skill` for Financial Market Intelligence.

## 1. DESIGN READ & THE THREE DIALS
* **Reading this as:** Institutional Market Intelligence & Equity Analytics Dashboard for financial analysts and sophisticated investors.
* **Dials Configuration:**
  - `DESIGN_VARIANCE: 5` (Structured, clean, balanced grid hierarchy, no chaotic misalignment)
  - `MOTION_INTENSITY: 3` (Restrained, functional transitions: hover states, drawer slide, smooth score fills. No distracting looping floaters)
  - `VISUAL_DENSITY: 8` (High data density: compact tables, tabular numbers, clear metric tags, no wasted whitespace)

## 2. ANTI-SLOP DISCIPLINE (STRICTLY FORBIDDEN)
* ❌ **NO AI Purple Gradients:** Do not use `from-purple-600 to-indigo-600` or neon cyan backgrounds.
* ❌ **NO Sparkle Clichés:** Do not place sparkle emojis (`✨`) or AI buzzword labels everywhere.
* ❌ **NO Excessive Glassmorphism:** Do not use blurry transparent cards (`backdrop-blur-md bg-white/10`) that ruin text legibility.
* ❌ **NO Giant "Card Soup":** Do not build massive bubbly cards (`rounded-3xl`) with 2 words of content. Use compact cards with clear data hierarchy.
* ❌ **NO Low Contrast Text:** All secondary text must pass WCAG AA contrast (e.g., `text-slate-400` on `bg-slate-900`, never faint gray on light gray).

## 3. COLOR PALETTE & SEMANTIC TOKENS
* **Neutral Base (Dark Mode default / Light Mode supported):**
  - Background: `bg-slate-950` / Surface: `bg-slate-900` / Borders: `border-slate-800`
  - Text Primary: `text-slate-100` / Text Muted: `text-slate-400` / Text Accent: `text-slate-300`
* **Semantic Impact Scores:**
  - **Resilient / Beneficiary (+1 to +10):** `emerald-500` / `emerald-400` / `bg-emerald-950/40 text-emerald-300 border-emerald-800/50`
  - **Neutral (0):** `slate-400` / `bg-slate-800/50 text-slate-300 border-slate-700`
  - **Vulnerable / Critical (-1 to -10):** `rose-500` / `rose-400` / `bg-rose-950/40 text-rose-300 border-rose-800/50`

## 4. TYPOGRAPHY & DATA PRESENTATION
* **UI Typography:** Plus Jakarta Sans / Inter Tight / system-ui (`font-sans`).
* **Financial Data & Tickers:** Always use monospace with tabular numbers (`font-mono tabular-nums`) for stock tickers (e.g., `BBCA`, `ADRO`), percentages, ratios (DER, NPM, PBV), and impact scores.
* **Badges & Pills:** Compact, crisp borders (`rounded-md px-2 py-0.5 text-xs font-medium border`).
