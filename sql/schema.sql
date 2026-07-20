-- Импортируй этот файл в свою MySQL базу (через phpMyAdmin: вкладка "Импорт").

CREATE TABLE IF NOT EXISTS works (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title       VARCHAR(255) NOT NULL,
    author      VARCHAR(255) NOT NULL DEFAULT 'Mary Holzer / Мария Гольцер',
    category    VARCHAR(50)  NOT NULL DEFAULT 'Рисунок',
    description TEXT NULL,
    image       VARCHAR(255) NOT NULL,
    sort_order  INT NOT NULL DEFAULT 0,
    created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Если база уже была создана раньше (без колонки category) — накати вручную:
-- ALTER TABLE works ADD COLUMN category VARCHAR(50) NOT NULL DEFAULT 'Рисунок' AFTER author;
