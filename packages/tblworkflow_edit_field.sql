CREATE TABLE tblworkflow_edit_field (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    workflow_id INT(11) NOT NULL,
    edit_type_id SMALLINT,
    edit_field_id SMALLINT,
    field_value VARCHAR
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;