-- Emergency Logs Table
-- Tracks all emergency detections for audit and analysis

CREATE TABLE IF NOT EXISTS emergency_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NULL,
    severity ENUM('CRITICAL', 'URGENT', 'ROUTINE') NOT NULL,
    primary_symptom VARCHAR(255),
    secondary_symptom VARCHAR(255),
    action_taken TEXT,
    detected_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    response_time INT NULL COMMENT 'Time to respond in seconds',
    outcome VARCHAR(100) NULL COMMENT 'Patient outcome',
    notes TEXT NULL,
    
    INDEX idx_severity (severity),
    INDEX idx_detected_at (detected_at),
    INDEX idx_user_id (user_id),
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
