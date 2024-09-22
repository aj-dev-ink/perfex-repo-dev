CREATE TABLE tblworkflow_edit_field (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    workflow_id INT(11) NOT NULL,
    edit_type_id SMALLINT,
    edit_field_id SMALLINT,
    field_value VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


ALTER TABLE `tblworkflow_edit_field`
CHANGE `field_value` `edit_field_value` VARCHAR(255);

ALTER TABLE `tblworkflow_edit_field`
ADD COLUMN `edit_custom_value` VARCHAR(255);