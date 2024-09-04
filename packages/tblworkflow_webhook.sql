CREATE TABLE tblworkflow_webhook (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    workflow_id INT(11),
    name VARCHAR(255),
    description mediumtext DEFAULT NULL,
    request_type SMALLINT,
    request_url VARCHAR(255),
    authorization_type SMALLINT,
    api_key VARCHAR(255),
    bearer_token VARCHAR(255),
    auth_username VARCHAR(255),
    auth_password VARCHAR(255),
    is_url_param BOOLEAN DEFAULT FALSE,
    url_params VARCHAR(500)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;