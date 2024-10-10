CREATE TABLE tblworkflow_processing_schedule (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    workflow_id INT(11),
    entity_type INT(11),
    entity_id INT(11),
    is_processed BOOLEAN DEFAULT 0,
    scheduled_time timestamp
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
