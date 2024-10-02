CREATE TABLE tblworkflow_send_email (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    workflow_id INT(11),
    template_id INT(11)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;