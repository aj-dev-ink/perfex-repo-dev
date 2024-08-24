CREATE TABLE tblworkflow_condition (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    workflow_id INT(11) NOT NULL,
    condition_type_id SMALLINT,
    stage_type_id SMALLINT,
    value_type_id SMALLINT,
    compare_value SMALLINT 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;