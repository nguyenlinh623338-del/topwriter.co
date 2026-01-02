# 🗺️ TỔNG QUAN HỆ THỐNG TOPWRITER + LACKEY.CCZ.ES

## Kiến Trúc Tổng Quan

```
┌─────────────────────────────────────────────────────────────────────────────────┐
│                              TỔNG QUAN KIẾN TRÚC                                │
├─────────────────────────────────────────────────────────────────────────────────┤
│                                                                                 │
│   ┌──────────────────┐         HTTP API          ┌──────────────────────────┐   │
│   │                  │  ──────────────────────▶  │                          │   │
│   │  LARAVEL APP     │                           │  PYTHON FLASK API        │   │
│   │  topwriter.co    │  ◀──────────────────────  │  lackey.ccz.es           │   │
│   │                  │         JSON Response     │                          │   │
│   └────────┬─────────┘                           └────────────┬─────────────┘   │
│            │                                                  │                 │
│            ▼                                                  ▼                 │
│   ┌──────────────────┐                           ┌──────────────────────────┐   │
│   │  SQLite/MySQL    │                           │  GOOGLE SHEETS           │   │
│   │  - users         │                           │  - Customer_Register     │   │
│   │  - dashboard_    │                           │  - Dashboard_[email]_*   │   │
│   │    sheets        │                           │                          │   │
│   │  - dashboard_    │                           │                          │   │
│   │    items         │                           │                          │   │
│   └──────────────────┘                           └──────────────────────────┘   │
│                                                                                 │
└─────────────────────────────────────────────────────────────────────────────────┘
```

---

## 📊 FLOW 1: ĐĂNG KÝ TRIAL & TẠO DASHBOARD

```
┌─────────────────────────────────────────────────────────────────────────────────┐
│                         FLOW ĐĂNG KÝ TRIAL WRITING                              │
├─────────────────────────────────────────────────────────────────────────────────┤
│                                                                                 │
│  👤 USER                                                                        │
│     │                                                                           │
│     │ Điền form: name, email, website, keywords, guidelines...                  │
│     ▼                                                                           │
│  ┌─────────────────────────────────────────────────────────────────────────┐    │
│  │  LARAVEL: TrialWritingController@store                                  │    │
│  │  ─────────────────────────────────────────────────────────────────────  │    │
│  │  1. Tạo User (nếu chưa có)                                              │    │
│  │  2. Tạo TrialRegistration                                               │    │
│  │  3. Gọi PythonApiService->registerCustomer() ──────────────────────┐    │    │
│  └───────────────────────────────────────────────────────────────────────┘ │    │
│                                                                            │    │
│                                                                            ▼    │
│  ┌─────────────────────────────────────────────────────────────────────────┐    │
│  │  PYTHON API: POST /api/register-customer                                │    │
│  │  ─────────────────────────────────────────────────────────────────────  │    │
│  │  Request JSON:                                                          │    │
│  │  {                                                                      │    │
│  │    "name": "Nguyễn Văn A",                                              │    │
│  │    "email": "user@example.com",                                         │    │
│  │    "website": "https://example.com",                                    │    │
│  │    "guidelines": "SEO guidelines...",                                   │    │
│  │    "register_date": "2025-01-01",                                       │    │
│  │    "keyword": "seo, content, marketing",                                │    │
│  │    "link_login": "https://cms.example.com",                             │    │
│  │    "username": "admin",                                                 │    │
│  │    "password": "xxx",                                                   │    │
│  │    "competitor_website": "https://competitor.com",                      │    │
│  │    "credit": 100                                                        │    │
│  │  }                                                                      │    │
│  └───────────────────────────────────────────────────────────────────────┬─┘    │
│                                                                          │      │
│                              ┌───────────────────────────────────────────┘      │
│                              ▼                                                  │
│  ┌─────────────────────────────────────────────────────────────────────────┐    │
│  │  STEP 1: register_to_sheet.py (service_account.json)                    │    │
│  │  ─────────────────────────────────────────────────────────────────────  │    │
│  │  Ghi vào Google Sheet: Customer_Register (ID: 18O5RNty...)              │    │
│  │                                                                         │    │
│  │  │ A   │ B      │ C     │ D       │ E    │ F      │ G          │ H    │ │    │
│  │  │ STT │ Họ Tên │ Email │ Website │ Link │ SheetID│ Guidelines │ Date │ │    │
│  │  │     │        │       │         │ Dash │        │            │      │ │    │
│  │  │ ... │ I          │ J        │ K        │ L              │ M      │ │    │
│  │  │     │ Link Login │ Username │ Password │ Competitor URL │ Credit │ │    │
│  │                                                                         │    │
│  │  ➜ Trả về: STT (số dòng vừa ghi)                                        │    │
│  └───────────────────────────────────────────────────────────────────────┬─┘    │
│                                                                          │      │
│                              ┌───────────────────────────────────────────┘      │
│                              ▼                                                  │
│  ┌─────────────────────────────────────────────────────────────────────────┐    │
│  │  STEP 2: create_dashboard.py (edu.json)                                 │    │
│  │  ─────────────────────────────────────────────────────────────────────  │    │
│  │  Tạo Google Sheet MỚI: Dashboard_{email}_{date}_{timestamp}             │    │
│  │                                                                         │    │
│  │  - Tách keyword thành nhiều dòng (mỗi keyword = 1 dòng)                 │    │
│  │  - Cấp quyền editor cho: dieuhanhweb@gmail.com, vietbaic@gmail.com      │    │
│  │                                                                         │    │
│  │  ➜ Trả về: {sheet_url, sheet_id}                                        │    │
│  └───────────────────────────────────────────────────────────────────────┬─┘    │
│                                                                          │      │
│                              ┌───────────────────────────────────────────┘      │
│                              ▼                                                  │
│  ┌─────────────────────────────────────────────────────────────────────────┐    │
│  │  STEP 3: Update Customer_Register                                       │    │
│  │  ─────────────────────────────────────────────────────────────────────  │    │
│  │  Cập nhật cột E (Link Dashboard) và F (Sheet ID) cho dòng STT           │    │
│  └───────────────────────────────────────────────────────────────────────┬─┘    │
│                                                                          │      │
│                              ┌───────────────────────────────────────────┘      │
│                              ▼                                                  │
│  ┌─────────────────────────────────────────────────────────────────────────┐    │
│  │  RESPONSE JSON:                                                         │    │
│  │  {"status": "ok", "sheet_url": "...", "sheet_id": "..."}                │    │
│  └───────────────────────────────────────────────────────────────────────┬─┘    │
│                                                                          │      │
│                              ┌───────────────────────────────────────────┘      │
│                              ▼                                                  │
│  ┌─────────────────────────────────────────────────────────────────────────┐    │
│  │  LARAVEL: Lưu vào database                                              │    │
│  │  ─────────────────────────────────────────────────────────────────────  │    │
│  │  dashboard_sheets:                                                      │    │
│  │    - user_id                                                            │    │
│  │    - trial_registration_id                                              │    │
│  │    - sheet_id ← từ API response                                         │    │
│  │    - sheet_url ← từ API response                                        │    │
│  │                                                                         │    │
│  │  ➜ Sau đó gọi syncDashboardData() để lấy data về dashboard_items        │    │
│  └─────────────────────────────────────────────────────────────────────────┘    │
│                                                                                 │
└─────────────────────────────────────────────────────────────────────────────────┘
```

---

## 📊 FLOW 2: SYNC DASHBOARD DATA

```
┌─────────────────────────────────────────────────────────────────────────────────┐
│                         FLOW SYNC DASHBOARD                                     │
├─────────────────────────────────────────────────────────────────────────────────┤
│                                                                                 │
│  👤 USER click nút "Sync" trên dashboard                                        │
│     │                                                                           │
│     ▼                                                                           │
│  ┌─────────────────────────────────────────────────────────────────────────┐    │
│  │  LARAVEL: DashboardSyncController@sync                                  │    │
│  │  Gọi: PythonApiService->syncDashboard(sheet_id)                         │    │
│  └───────────────────────────────────────────────────────────────────────┬─┘    │
│                                                                          │      │
│                              ┌───────────────────────────────────────────┘      │
│                              ▼                                                  │
│  ┌─────────────────────────────────────────────────────────────────────────┐    │
│  │  PYTHON API: POST /api/syns-dashboard                                   │    │
│  │  ─────────────────────────────────────────────────────────────────────  │    │
│  │  Request: {"sheet_id": "1ABC...XYZ"}                                    │    │
│  │                                                                         │    │
│  │  sync_dashboard_to_json.py đọc Google Sheet và trả về:                  │    │
│  │  [                                                                      │    │
│  │    {                                                                    │    │
│  │      "stt": 1,                                                          │    │
│  │      "email": "user@example.com",                                       │    │
│  │      "keyword": "seo content",                                          │    │
│  │      "link_top": "https://example.com/article",                         │    │
│  │      "idea": "Ý tưởng hay...",                                          │    │
│  │      "link_docs": "https://docs.google.com/...",                        │    │
│  │      "link_post": "https://example.com/post",                           │    │
│  │      "revision": "Cần sửa phần intro",                                  │    │
│  │      "status": "0"                                                      │    │
│  │    },                                                                   │    │
│  │    ...                                                                  │    │
│  │  ]                                                                      │    │
│  └───────────────────────────────────────────────────────────────────────┬─┘    │
│                                                                          │      │
│                              ┌───────────────────────────────────────────┘      │
│                              ▼                                                  │
│  ┌─────────────────────────────────────────────────────────────────────────┐    │
│  │  LARAVEL: Cập nhật dashboard_items                                      │    │
│  │  ─────────────────────────────────────────────────────────────────────  │    │
│  │  1. Xóa tất cả items cũ của dashboard_sheet_id                          │    │
│  │  2. Tạo mới từng item từ JSON response                                  │    │
│  └─────────────────────────────────────────────────────────────────────────┘    │
│                                                                                 │
└─────────────────────────────────────────────────────────────────────────────────┘
```

---

## 📋 MAPPING CỘT GOOGLE SHEET ↔ LARAVEL DATABASE

### Cấu Trúc Google Sheet Dashboard

```
┌─────┬─────────┬─────────┬──────────────┬──────────┬──────────┬─────────┐
│  A  │    B    │    C    │      D       │    E     │    F     │    G    │
├─────┼─────────┼─────────┼──────────────┼──────────┼──────────┼─────────┤
│ STT │ Họ Tên  │  Email  │Website Khách │ Keyword  │ Link Top │  Ý hay  │
└─────┴─────────┴─────────┴──────────────┴──────────┴──────────┴─────────┘

┌────────────┬─────────────────┬───────────┬───────────┬──────────┐
│     H      │        I        │     J     │     K     │    L     │
├────────────┼─────────────────┼───────────┼───────────┼──────────┤
│ Guidelines │ Link Google Docs│ Link Post │ Revisions │  Status  │
└────────────┴─────────────────┴───────────┴───────────┴──────────┘
```

### Bảng Mapping Chi Tiết

| Google Sheet Column | Python Key | Laravel DB Field |
|---------------------|------------|------------------|
| A - STT | stt | order (dùng để sắp xếp) |
| B - Họ Tên | (không sync) | - |
| C - Email | email | (không lưu, đã có user_id) |
| D - Website Khách | (không sync) | - |
| E - Keyword | keyword | keyword |
| F - Link Top | link_top | link_top |
| G - Ý hay | idea | idea |
| H - Guidelines | (không sync) | guidelines (có thể null) |
| I - Link Google Docs | link_docs | link_docs |
| J - Link Post | link_post | link_post |
| K - Revisions | revision | revision |
| L - Status | status | revision_status |

---

## 📊 FLOW 3: THÊM KEYWORD MỚI

```
┌─────────────────────────────────────────────────────────────────────────────────┐
│                         FLOW THÊM KEYWORD MỚI                                   │
├─────────────────────────────────────────────────────────────────────────────────┤
│                                                                                 │
│  LARAVEL: PythonApiService->addKeywords(email, keywords[], credits)             │
│     │                                                                           │
│     ▼                                                                           │
│  ┌─────────────────────────────────────────────────────────────────────────┐    │
│  │  PYTHON API: POST /api/add-keyword                                      │    │
│  │  ─────────────────────────────────────────────────────────────────────  │    │
│  │  Request:                                                               │    │
│  │  {                                                                      │    │
│  │    "email": "user@example.com",                                         │    │
│  │    "new_keywords": ["keyword1", "keyword2"],                            │    │
│  │    "credit": 80                                                         │    │
│  │  }                                                                      │    │
│  │                                                                         │    │
│  │  Logic:                                                                 │    │
│  │  1. Tìm user trong Customer_Register theo email                         │    │
│  │  2. Lấy dashboard_id từ cột F                                           │    │
│  │  3. Mở Dashboard riêng của user                                         │    │
│  │  4. Kiểm tra keyword đã tồn tại chưa → bỏ qua nếu có                    │    │
│  │  5. Copy Họ Tên, Website, Guidelines từ dòng 2                          │    │
│  │  6. Append dòng mới cho mỗi keyword                                     │    │
│  │  7. Update credit trong Customer_Register                               │    │
│  └─────────────────────────────────────────────────────────────────────────┘    │
│                                                                                 │
└─────────────────────────────────────────────────────────────────────────────────┘
```

---

## 📊 FLOW 4: CẬP NHẬT REVISION

```
┌─────────────────────────────────────────────────────────────────────────────────┐
│                         FLOW CẬP NHẬT REVISION                                  │
├─────────────────────────────────────────────────────────────────────────────────┤
│                                                                                 │
│  LARAVEL: PythonApiService->updateRevision(sheet_id, revisions[])               │
│     │                                                                           │
│     ▼                                                                           │
│  ┌─────────────────────────────────────────────────────────────────────────┐    │
│  │  PYTHON API: POST /api/update-revision                                  │    │
│  │  ─────────────────────────────────────────────────────────────────────  │    │
│  │  Request:                                                               │    │
│  │  {                                                                      │    │
│  │    "sheet_id": "1ABC...XYZ",                                            │    │
│  │    "revisions": [                                                       │    │
│  │      {"keyword": "seo content", "note": "Cần sửa intro"},               │    │
│  │      {"keyword": "marketing", "note": "Thêm ví dụ"}                     │    │
│  │    ]                                                                    │    │
│  │  }                                                                      │    │
│  │                                                                         │    │
│  │  update_revision.py:                                                    │    │
│  │  - Tìm dòng có keyword tương ứng (cột E)                                │    │
│  │  - Cập nhật cột K (Revisions) = note                                    │    │
│  │  - Cập nhật cột L (Status) = "0" (chưa sửa)                             │    │
│  └─────────────────────────────────────────────────────────────────────────┘    │
│                                                                                 │
└─────────────────────────────────────────────────────────────────────────────────┘
```

---

## 🗄️ DATABASE SCHEMA (LARAVEL)

```
┌─────────────────────────────────────────────────────────────────────────────────┐
│                            DATABASE RELATIONSHIPS                               │
├─────────────────────────────────────────────────────────────────────────────────┤
│                                                                                 │
│  ┌─────────────────┐     1:N      ┌─────────────────────┐                       │
│  │     users       │─────────────▶│ trial_registrations │                       │
│  │                 │              │                     │                       │
│  │  - id           │              │  - id               │                       │
│  │  - name         │              │  - user_id (FK)     │                       │
│  │  - email        │              │  - keywords         │                       │
│  │  - credits      │              │  - website_url      │                       │
│  └────────┬────────┘              │  - guidelines       │                       │
│           │                       │  - payment_*        │                       │
│           │                       └──────────┬──────────┘                       │
│           │                                  │                                  │
│           │ 1:N                              │ 1:1                              │
│           ▼                                  ▼                                  │
│  ┌─────────────────────────────────────────────────────────────┐                │
│  │                    dashboard_sheets                          │                │
│  │                                                              │                │
│  │  - id                                                        │                │
│  │  - user_id (FK)                                              │                │
│  │  - trial_registration_id (FK)                                │                │
│  │  - sheet_id ◀──────── Google Sheet ID                        │                │
│  │  - sheet_url ◀─────── Google Sheet URL                       │                │
│  │  - last_synced_at                                            │                │
│  └──────────────────────────────┬───────────────────────────────┘                │
│                                 │                                               │
│                                 │ 1:N                                           │
│                                 ▼                                               │
│  ┌─────────────────────────────────────────────────────────────┐                │
│  │                    dashboard_items                           │                │
│  │                                                              │                │
│  │  - id                                                        │                │
│  │  - dashboard_sheet_id (FK)                                   │                │
│  │  - keyword           ◀──────── Cột E Google Sheet            │                │
│  │  - link_top          ◀──────── Cột F                         │                │
│  │  - idea              ◀──────── Cột G (Ý hay)                 │                │
│  │  - guidelines        ◀──────── Cột H                         │                │
│  │  - link_docs         ◀──────── Cột I                         │                │
│  │  - link_post         ◀──────── Cột J                         │                │
│  │  - revision          ◀──────── Cột K                         │                │
│  │  - revision_status   ◀──────── Cột L                         │                │
│  │  - order             ◀──────── Cột A (STT)                   │                │
│  └─────────────────────────────────────────────────────────────┘                │
│                                                                                 │
└─────────────────────────────────────────────────────────────────────────────────┘
```

---

## 🔐 SERVICE ACCOUNT PHÂN QUYỀN

```
┌─────────────────────────────────────────────────────────────────────────────────┐
│                         SERVICE ACCOUNT PHÂN QUYỀN                              │
├─────────────────────────────────────────────────────────────────────────────────┤
│                                                                                 │
│  ┌─────────────────────────┐          ┌─────────────────────────┐               │
│  │   service_account.json  │          │       edu.json          │               │
│  │   ─────────────────────  │          │   ─────────────────────  │               │
│  │                         │          │                         │               │
│  │   Dùng cho:             │          │   Dùng cho:             │               │
│  │   ✓ Customer_Register   │          │   ✓ Dashboard riêng     │               │
│  │     (file chung)        │          │     của từng user       │               │
│  │                         │          │                         │               │
│  │   Thao tác:             │          │   Thao tác:             │               │
│  │   - register_customer   │          │   - create_dashboard    │               │
│  │   - update credit       │          │   - add_keyword         │               │
│  │   - get dashboard_id    │          │   - sync_dashboard      │               │
│  │                         │          │   - update_revision     │               │
│  └─────────────────────────┘          └─────────────────────────┘               │
│                                                                                 │
│  ⚠️ Nếu dùng SAI JSON file sẽ bị lỗi permission!                               │
│                                                                                 │
└─────────────────────────────────────────────────────────────────────────────────┘
```

---

## 📁 CẤU TRÚC FILE PYTHON API (lackey.ccz.es)

| File | Vai trò |
|------|---------|
| `app.py` | API server chính, định nghĩa các routes |
| `register_to_sheet.py` | Ghi thông tin khách hàng vào Customer_Register |
| `create_dashboard.py` | Tạo Google Sheet Dashboard riêng cho từng khách |
| `sync_dashboard_to_json.py` | Đọc dashboard và trả về JSON |
| `update_revision.py` | Cập nhật góp ý chỉnh sửa vào dashboard |
| `service_account.json` | Credentials cho Customer_Register |
| `edu.json` | Credentials cho các Dashboard riêng |

---

## 🔗 CÁC API ENDPOINTS

### 1. POST /api/register-customer
- **Mục đích**: Đăng ký khách mới + tạo Dashboard
- **Input**: name, email, website, guidelines, register_date, keyword, link_login, username, password, competitor_website, credit
- **Output**: `{"status": "ok", "sheet_url": "...", "sheet_id": "..."}`

### 2. POST /api/syns-dashboard
- **Mục đích**: Đồng bộ dữ liệu từ Dashboard về JSON
- **Input**: `{"sheet_id": "..."}`
- **Output**: Array of items với các fields: stt, email, keyword, link_top, idea, link_docs, link_post, revision, status

### 3. POST /api/add-keyword
- **Mục đích**: Thêm keywords mới vào Dashboard
- **Input**: `{"email": "...", "new_keywords": [...], "credit": ...}`
- **Output**: `{"status": "ok", "added_keywords": [...]}`

### 4. POST /api/update-revision
- **Mục đích**: Cập nhật góp ý chỉnh sửa
- **Input**: `{"sheet_id": "...", "revisions": [{"keyword": "...", "note": "..."}]}`
- **Output**: `{"status": "ok", "results": [...]}`

---

## 📝 GHI CHÚ QUAN TRỌNG

1. **Mỗi user có 1 Dashboard riêng** - được tạo khi đăng ký trial
2. **Dashboard lưu trên Google Sheets** - Laravel chỉ cache data trong `dashboard_items`
3. **Sync là 2 chiều**:
   - Laravel → Google Sheet: qua add-keyword, update-revision
   - Google Sheet → Laravel: qua syns-dashboard
4. **2 Service Account riêng biệt** để phân quyền chặt chẽ
5. **Keywords được tách thành nhiều dòng** - mỗi keyword = 1 dòng trong Dashboard

---

*Tài liệu được tạo: December 2024*








