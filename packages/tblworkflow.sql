CREATE TABLE tblworkflow (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    description mediumtext DEFAULT NULL,
    entity_type_id SMALLINT,
    action_type_id SMALLINT,
    is_trigger_now BOOLEAN DEFAULT TRUE,
    is_condition_based BOOLEAN DEFAULT TRUE,
    trigger_type_id SMALLINT

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 
ALTER TABLE `tblworkflow`
ADD COLUMN `delay_action_time` TIMESTAMP NULL DEFAULT NULL;