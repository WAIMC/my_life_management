# Convention Summary
- Directory: app/{Master, Management, History}/{Master, Management}/{Component}
- Naming: PascalCase for classes (e.g., CategoryMst), snake_case for tables (e.g., category_mst)
- Migration: yyyy_mm_dd_hhmmss_create_[table]_[scope].php
- Layers: Migration → Model → Repository → Interface → Service → Controller → Request → Resource → Route