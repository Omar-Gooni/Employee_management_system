CREATE DATABASE IF NOT EXISTS employee_management_system;
USE employee_management_system;
CREATE TABLE admin (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    phone VARCHAR(20) UNIQUE,
    gender VARCHAR(10),
    date_joined DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(20) DEFAULT 'Active',
    password VARCHAR(255),
    image VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    role VARCHAR(50) DEFAULT 'admin'
);
CREATE TABLE departments (
    department_id INT AUTO_INCREMENT PRIMARY KEY,
    department_name VARCHAR(100) NOT NULL,
    description TEXT,
    location VARCHAR(100),
    head_of_department INT,
    FOREIGN KEY (head_of_department) REFERENCES admin(admin_id)
        ON DELETE SET NULL
);
CREATE TABLE employees (
    emp_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    phone VARCHAR(20) UNIQUE,
    gender VARCHAR(10),
    department_id INT NULL,
    position VARCHAR(50) DEFAULT 'employee',
    employee_type VARCHAR(50),
    date_joined DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(20) DEFAULT 'Active',
    password VARCHAR(255),
    image VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (department_id) REFERENCES departments(department_id)
        ON DELETE SET NULL
);
ALTER TABLE employees
ADD COLUMN employee_type_id INT,
ADD COLUMN salary DECIMAL(10, 2),
ADD CONSTRAINT fk_employee_type
    FOREIGN KEY (employee_type_id) REFERENCES employee_types(type_id)
    ON DELETE SET NULL;




CREATE TABLE attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    emp_id INT NOT NULL,
    date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    attendance_date DATE NOT NULL,
    check_in TIME,
    check_out TIME,
    status VARCHAR(20) DEFAULT 'Absent',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    CONSTRAINT fk_emp FOREIGN KEY (emp_id) REFERENCES employees(emp_id) ON DELETE CASCADE,
    UNIQUE (emp_id, attendance_date)
);
CREATE TABLE tasks (
    task_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    description TEXT,
    start_date DATE,
    end_date DATE,
    due_date DATE,
    status VARCHAR(50)
);
ALTER TABLE tasks 
ADD COLUMN budget DECIMAL(10, 2) DEFAULT 0;

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
    is_seen_admin BOOLEAN DEFAULT FALSE,
    is_seen_employee BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (employee_id) REFERENCES employees(emp_id)
        ON DELETE CASCADE
);


CREATE TABLE employee_types (
    type_id INT AUTO_INCREMENT PRIMARY KEY,
    type_name VARCHAR(100) NOT NULL,
    default_salary DECIMAL(10, 2) NOT NULL
);
INSERT INTO employee_types (type_name, default_salary)
VALUES 
    ('Full Time', 500.00),
    ('Shift', 400.00),
    ('Part Time', 250.00);







insert into admin (
    name,
    email,
    phone,
    gender,
    status,
    password,
    image,
    role
)
values(
"Omar Mohamuud Gooni",
"omar@gmail.com",
"617999682",
"male",
"Active",
"123",
"default.png",
"admin"

);






CREATE TABLE issues (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    issue_type VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    status ENUM('Pending', 'In Progress', 'Resolved', 'Rejected') DEFAULT 'Pending',
    admin_comment TEXT,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_seen_admin BOOLEAN DEFAULT FALSE,
    is_seen_employee BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (employee_id) REFERENCES employees(emp_id) ON DELETE CASCADE
);