
// create Employee
function create_employee() {
    const popup = document.getElementById('creat_emp_popup');
    popup.classList.toggle('hidden');
}
// update employee

    function update_employee(empId, empName, empEmail, empPassword, empJobTitle, empSalary, empDepID) {
        const popup = document.getElementById('update_emp_popup');
       
        popup.classList.toggle('hidden');
        // Set the values in the form fields
        document.querySelector('input[name="emp_id"]').value = empId;  // Populate the hidden employee ID field
        document.querySelector('input[name="emp_name"]').value = empName;
        document.querySelector('input[name="emp_email"]').value = empEmail;
        document.querySelector('input[name="emp_password"]').value = empPassword;  // Display the real password
        document.querySelector('input[name="job_tital"]').value = empJobTitle;
        document.querySelector('input[name="emp_Salary"]').value = empSalary;
        document.querySelector('input[name="Department_ID"]').value = empDepID;
    }
    function closePopup() {
        const popup = document.getElementById('update_emp_popup');
        popup.classList.toggle('hidden');
    }
    
    

// update department
function update_department(id, name, manager_id) {
    const popup = document.getElementById('update_dep_popup');
    
    // Set the value of the hidden input field for department ID
    document.getElementById('dep_id').value = id;
    
    // Populate the fields with the existing department data
    document.querySelector('input[name="Department_Name"]').value = name;
    document.querySelector('input[name="Manger_id"]').value = manager_id;

    // Show the popup
    popup.classList.toggle('hidden');
}

function closePopup(){
    const popup = document.getElementById('update_dep_popup');
    popup.classList.toggle('hidden');
}


// update projecs
function updateProject(id, name, startDate, endDate, budget, departmentId) {
    const popup = document.getElementById('update_pro_popup');

    // Populate popup fields with values
    document.getElementById('update_project_id').value = id; // Set Project ID
    document.getElementById('update_project_name').value = name; // Set Project Name
    document.getElementById('update_start_date').value = startDate; // Set Start Date
    document.getElementById('update_end_date').value = endDate; // Set End Date
    document.getElementById('update_budget').value = budget; // Set Budget
    document.getElementById('update_department_id').value = departmentId; // Set Department ID

    // Show the popup
    popup.classList.remove('hidden');
}



// updating Attendance
function showUpdatePopup(empId, status, checkIn, checkOut) {
    const popup = document.getElementById('update_popup');

    // Populate the form fields
    document.querySelector('input[name="Emp_ID"]').value = empId;
    document.querySelector('select[name="Status"]').value = status;
    document.querySelector('input[name="Check_in"]').value = checkIn !== "NULL" ? checkIn : "";
    document.querySelector('input[name="Check_out"]').value = checkOut !== "NULL" ? checkOut : "";

    // Show the popup
    popup.classList.remove('hidden');
}

function closePopup() {
    const popup = document.getElementById('update_popup');
    popup.classList.add('hidden');
}



// update employee_project

function updateEmpProject(id, empId, projectId) {
    // Show the popup
    const popup = document.getElementById('update_em_pro_popup');
    popup.classList.remove('hidden');
    
    // Populate the form fields with the values
    document.querySelector('[name="Employee_Projects_ID"]').value = id;
    document.querySelector('[name="Employee_ID"]').value = empId;
    document.querySelector('[name="Project_ID"]').value = projectId;
}

function close_pop() {
    const popup = document.getElementById('update_em_pro_popup');
    popup.classList.toggle('hidden');
  }





//   updating admin

function update_admin_popup(id, name, email, password) {
    // Show the popup
    document.getElementById('update_admin_popup').classList.remove('hidden');
    
    // Populate form fields
    document.querySelector('[name="admin_id"]').value = id; // Hidden Admin ID
    document.querySelector('[name="name"]').value = name;
    document.querySelector('[name="email"]').value = email;
    document.querySelector('[name="password"]').value = password;
}

function close_update_Popup() {
    document.getElementById('update_admin_popup').classList.add('hidden');
}


