
CREATE TABLE student_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    usn VARCHAR(20) UNIQUE,
    email VARCHAR(100),
    phone VARCHAR(15),
    department VARCHAR(50),
    semester INT,
        Bio TEXT,
    tenth_aggr FLOAT,
    twelveth_aggr FLOAT,
    engg_aggr FLOAT,
    section VARCHAR(10),
    branch VARCHAR(10),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


CREATE TABLE professional_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    technical_skills VARCHAR(255),
    technology_interested_in VARCHAR(255),
    professional_skills VARCHAR(255),
    certification VARCHAR(255),
    professional_bodies VARCHAR(255),
    professional_role VARCHAR(255),
    projects TEXT,
    internships TEXT,
    areas_of_interest VARCHAR(50),
    counsellor VARCHAR(255),
        feedback TEXT,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES student_details(id) ON DELETE CASCADE
);

CREATE TABLE socials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    linkedin VARCHAR(255),
    github VARCHAR(255),
    resume VARCHAR(255),
    photo VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES student_details(id) ON DELETE CASCADE
);


CREATE TABLE users (
  user_id INT PRIMARY KEY AUTO_INCREMENT, 
  Full_Name VARCHAR(50) NOT NULL, 
  username VARCHAR(50) NOT NULL UNIQUE, 
  phone_number VARCHAR(20),              
  email VARCHAR(100) NOT NULL UNIQUE,  
  password VARCHAR(255) NOT NULL,        
  user_type VARCHAR(20) NOT NULL         
);
