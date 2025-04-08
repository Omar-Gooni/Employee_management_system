use MySQL Workbench 


after install MySQL Workbench 


CREATE database mployee_management_system;


use employee_management_system;
-- 1. Departments Table
CREATE TABLE departments (
    department_id INT AUTO_INCREMENT PRIMARY KEY,
    department_name VARCHAR(100) NOT NULL
);


-- 2. Employees Table
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
    FOREIGN KEY (department_id) REFERENCES departments(department_id)
        ON DELETE SET NULL
);

-- 3. Attendance Table
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

-- 4. Tasks Table
CREATE TABLE tasks (
    task_id INT AUTO_INCREMENT PRIMARY KEY,
    emp_id INT NOT NULL,
    title VARCHAR(255),
    description TEXT,
    start_date DATE,
    end_date DATE,
    status VARCHAR(20), -- Pending, In Progress, Completed
    FOREIGN KEY (emp_id) REFERENCES employees(emp_id)
        ON DELETE CASCADE
);

-- 5. Admin Table
CREATE TABLE admin (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);