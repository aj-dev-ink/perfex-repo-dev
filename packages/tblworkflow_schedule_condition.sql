CREATE TABLE tblworkflow_schedule_condition (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    workflow_id INT(11) NOT NULL,
    sched_condition_type_id SMALLINT,
    sched_stage_type_id SMALLINT,
    sched_value_type_id SMALLINT,
    sched_operator_type_id SMALLINT,
    sched_compare_value_type_id SMALLINT,
    sched_actual_compare_value VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;