CREATE TABLE IF NOT EXISTS voucher_templates
(
    id     INTEGER PRIMARY KEY AUTOINCREMENT,
    name   TEXT UNIQUE,
    header TEXT,
    row    TEXT,
    footer TEXT
)