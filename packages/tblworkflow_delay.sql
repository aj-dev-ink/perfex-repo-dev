CREATE TABLE tblworkflow_delay (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    workflow_id INT(11) NOT NULL,
    pref_count INT(11),
    pref_duration SMALLINT,
    is_before BOOLEAN DEFAULT TRUE,
    delay_date_type SMALLINT,
    repeat_type SMALLINT,
    is_recurance BOOLEAN DEFAULT TRUE,
    frequency INT(11),
    until_date TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;