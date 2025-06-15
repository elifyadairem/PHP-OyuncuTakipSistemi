

USE dbstorage23360859744;

-- Kullanıcılar tablosu
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    birth_date DATE NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL
);

-- Oyun kayıtları tablosu
CREATE TABLE game_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    game_name VARCHAR(100) NOT NULL,
    game_mode VARCHAR(50) NOT NULL,
    score INT NOT NULL DEFAULT 0,
    level_reached INT NOT NULL DEFAULT 1,
    play_time_minutes INT NOT NULL DEFAULT 0,
    achievement VARCHAR(255),
    date_played DATE NOT NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- İndeksler
CREATE INDEX idx_user_id ON game_records(user_id);
CREATE INDEX idx_game_name ON game_records(game_name);
CREATE INDEX idx_date_played ON game_records(date_played);

-- Örnek kullanıcı ekleme (test için)
INSERT INTO users (fullname, birth_date, username, email, password_hash)
VALUES ('Admin-User', '1990-01-01', 'admin', 'admin@gametracker.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Örnek oyun kayıtları (test için)
INSERT INTO game_records (user_id, game_name, game_mode, score, level_reached, play_time_minutes, achievement, date_played, notes) VALUES
(1, 'League of Legends', 'Ranked Solo', 1250, 15, 45, 'First Blood', '2024-01-15', 'Great game, carried the team'),
(1, 'Counter-Strike 2', 'Competitive', 2800, 20, 60, 'Ace Round', '2024-01-16', 'Amazing clutch moments'),
(1, 'Valorant', 'Unrated', 1800, 12, 35, 'Team MVP', '2024-01-17', 'Good team coordination');
