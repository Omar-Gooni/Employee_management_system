CREATE DATABASE IF NOT EXISTS employee_management_system;
USE employee_management_system;

CREATE TABLE departments (
    department_id INT AUTO_INCREMENT PRIMARY KEY,
    department_name VARCHAR(100) NOT NULL
);



CREATE TABLE employees (
    emp_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100),
    phone VARCHAR(20),
    gender VARCHAR(10),
    department_id INT NULL,
    position VARCHAR(50),
    date_joined DATE,
    status VARCHAR(20) DEFAULT 'Active',
    password VARCHAR(255),
    image VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (department_id) REFERENCES departments(department_id)
        ON DELETE SET NULL
);
ALTER TABLE employees
MODIFY COLUMN position VARCHAR(50) DEFAULT 'employee';

ALTER TABLE employees
MODIFY COLUMN date_joined DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP;


CREATE TABLE attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    emp_id INT NOT NULL,
    date DATE NOT NULL,
    check_in TIME,
    check_out TIME,
    status VARCHAR(20) DEFAULT 'Absent',
    FOREIGN KEY (emp_id) REFERENCES employees(emp_id)
        ON DELETE CASCADE
);

ALTER TABLE attendance
MODIFY COLUMN date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP;



CREATE TABLE tasks (
    task_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    description TEXT,
    due_date DATE
);

ALTER TABLE tasks
ADD COLUMN status VARCHAR(50);
ALTER TABLE tasks
ADD COLUMN start_date DATE,
ADD COLUMN end_date DATE;




CREATE TABLE employee_task (
    id INT AUTO_INCREMENT PRIMARY KEY,
    emp_id INT,
    task_id INT,
    assigned_date DATE,
    status VARCHAR(50) DEFAULT 'Pending',
    FOREIGN KEY (emp_id) REFERENCES employees(emp_id)
        ON DELETE CASCADE,
    FOREIGN KEY (task_id) REFERENCES tasks(task_id)
        ON DELETE CASCADE
);

ALTER TABLE employee_task
CHANGE COLUMN emp_id employee_task_id INT;


CREATE TABLE admin (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100),
    password VARCHAR(255),
    image VARCHAR(255),
    role VARCHAR(50) DEFAULT 'admin'
);

CREATE TABLE leave_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    leave_type VARCHAR(50) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    reason TEXT,
    status ENUM('Pending', 'Approved', 'Rejected') DEFAULT 'Pending',
    hr_comment TEXT,
    request_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (employee_id) REFERENCES employees(emp_id) ON DELETE CASCADE
);
ALTER TABLE leave_requests 
ADD COLUMN is_seen_admin BOOLEAN DEFAULT FALSE,
ADD COLUMN is_seen_employee BOOLEAN DEFAULT FALSE;



