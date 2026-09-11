# Design Specification: Kilas Balik Krisis Historis (Historical Shock Backtest)

## 1. Executive Summary & Problem Statement
Dalam kompetisi analitik pasar modal (Sectors Hackathon 2026), model AI simulasi makro sering kali menghadapi pertanyaan kritis dari dewan juri dan analis institusional:
> *"Bagaimana kita dapat mempercayai bahwa model transmisi guncangan makro ini valid dan mencerminkan dinamika dunia nyata?"*

Fitur **Kilas Balik Krisis Historis (Historical Shock Backtest)** menjawab keraguan tersebut dengan menyajikan validasi empiris berbasis data nyata historis Indonesia. Modul ini membuktikan bahwa model AI MacroSectors mampu memprediksi dengan presisi tinggi arah pergerakan dan kerentanan 11 sektor Bursa Efek Indonesia (BEI) pada 3 krisis nyata:
1. **Taper Tantrum 2013**
2. **Perang Dagang AS–Tiongkok 2018**
3. **Market Crash Pandemi Covid-19 2020**

---

## 2. Arsitektur Komponen & Data Flow

### 2.1 Routing & Controller
- **Route**: `GET /backtest` -> `HistoricalBacktestController@index` (Route Name: `backtest.index`)
- **Query Parameter**: `?crisis={taper_tantrum_2013|trade_war_2018|covid_crash_2020}` (default: `covid_crash_2020`)

### 2.2 Model & Dataset Empiris
Dataset krisis historis dikompilasi secara terstruktur mencakup:
- **Metrik Makro Riil**:
  - Perubahan Suku Bunga BI (bps)
  - Depresiasi Kurs USD/IDR (%)
  - Inflasi / Kontraksi PDB (%)
  - Penurunan Total IHSG (% drawdown puncak ke dasar)
- **Data Performa Riil 11 Sektor**:
  - `actual_return`: Real drawdown / return sektor historis di BEI.
  - `predicted_impact`: Skor dampak prediksi model MacroSectors (-100 hingga +100).
  - `accuracy_status`: `Bullseye (Sangat Akurat)`, `Konsisten`, atau `Divergen Minor`.
  - `key_driver`: Alasan ekonomi di balik ketahanan atau kejatuhan sektor terkait.

### 2.3 Metrik Kuantitatif Keandalan Model
- **Directional Accuracy**: Persentase sektor di mana arah prediksi model (+ / -) selaras 100% dengan kenyataan historis (target akurasi model: >90%).
- **Top Predicted Resilient vs Actual**: Sektor paling kokoh hasil prediksi vs kenyataan.
- **Top Predicted Vulnerable vs Actual**: Sektor paling rentan hasil prediksi vs kenyataan.

---

## 3. Desain Antarmuka Pengguna (UI/UX)
Menggunakan standar visual institusional yang konsisten dengan modul `portfolio` dan `scanner`:
1. **Top App Bar**: Judul modul, badge `EMPIRICAL VALIDATION`, dan indikator status dataset.
2. **Crisis Timeline / Event Switcher**: 3 kartu/tombol interaktif dengan penanda krisis, tanggal periode, dan ringkasan guncangan.
3. **Macro Context Deck**: Ringkasan indikator makro riil saat krisis (Kurs, BI-Rate, Inflasi, Penurunan IHSG).
4. **Model Validation Scorecard (3 KPI Cards)**:
   - *Tingkat Akurasi Arah Prediksi (Directional Accuracy)*: e.g., 91% (10/11 Sektor).
   - *Sektor Paling Tangguh*: Prediksi vs Kenyataan.
   - *Sektor Paling Rentan*: Prediksi vs Kenyataan.
5. **Comparative 11-Sector Matrix**:
   - Bar visualisasi komparatif: Nilai Realita Historis vs Nilai Prediksi Model.
   - Label badge akurasi per sektor.
6. **Gemini AI Retrospective Post-Mortem**:
   - Analisis naratif mendalam: Pelajaran apa yang dapat diambil investor hari ini jika krisis serupa berulang.

---

## 4. Navigasi & Sidebar
Menambahkan menu navigasi ke-6 pada `resources/views/layouts/sidebar.blade.php`:
- Ikon: Timeline / History icon.
- Label: `Kilas Balik Krisis`
- Badge: `HISTORIS`

---

## 5. Rencana Pengujian (Test Suite)
Membuat `tests/Feature/HistoricalBacktestTest.php` yang memverifikasi:
- Halaman `/backtest` dapat diakses dan merender HTTP 200.
- Penggantian skenario krisis (`taper_tantrum_2013`, `trade_war_2018`, `covid_crash_2020`) berjalan sukses.
- Kalkulasi akurasi arah dan komparasi 11 sektor terisi lengkap.
- Menu link `backtest` tersedia di sidebar.
