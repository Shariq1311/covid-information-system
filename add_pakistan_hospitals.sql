-- Add Pakistani Hospitals to the Database
-- This file adds major hospitals from various cities across Pakistan

USE covid_portal;

-- Insert Pakistani Hospitals
INSERT INTO hospitals (
    hospital_name, 
    registration_number, 
    license_number, 
    contact_person, 
    phone, 
    email, 
    address, 
    city, 
    state, 
    pincode, 
    facilities, 
    specializations, 
    bed_capacity, 
    available_beds, 
    covid_testing, 
    vaccination_available, 
    approval_status
) VALUES 

-- Karachi Hospitals
('Aga Khan University Hospital', 'AKU001', 'LIC001', 'Dr. Ahmed Ali', '+92-21-34864751', 'info@aku.edu', 'Stadium Road, Karachi', 'Karachi', 'Sindh', '74800', 'Emergency, ICU, Laboratory, Pharmacy, Radiology', 'Cardiology, Oncology, Neurology, General Medicine', 500, 150, 1, 1, 'approved'),

('Jinnah Postgraduate Medical Centre', 'JPMC001', 'LIC002', 'Dr. Sarah Khan', '+92-21-99201300', 'contact@jpmc.edu.pk', 'Rafiqui Shaheed Road, Karachi', 'Karachi', 'Sindh', '75510', 'Emergency, ICU, Laboratory, Blood Bank', 'General Surgery, Internal Medicine, Pediatrics', 800, 200, 1, 1, 'approved'),

('Liaquat National Hospital', 'LNH001', 'LIC003', 'Dr. Muhammad Hassan', '+92-21-34412001', 'info@lnh.edu.pk', 'Stadium Road, Karachi', 'Karachi', 'Sindh', '74800', 'Emergency, ICU, Laboratory, Cardiac Care', 'Cardiology, Neurosurgery, Oncology', 400, 120, 1, 1, 'approved'),

('South City Hospital', 'SCH001', 'LIC004', 'Dr. Fatima Sheikh', '+92-21-34311820', 'info@southcity.com.pk', 'Clifton Block 4, Karachi', 'Karachi', 'Sindh', '75600', 'Emergency, ICU, Laboratory, Maternity', 'General Medicine, Pediatrics, Gynecology', 200, 60, 1, 1, 'approved'),

-- Lahore Hospitals
('Services Hospital Lahore', 'SHL001', 'LIC005', 'Dr. Ali Raza', '+92-42-99231068', 'admin@serviceshosp.com', 'Jail Road, Lahore', 'Lahore', 'Punjab', '54000', 'Emergency, ICU, Laboratory, Burn Unit', 'General Surgery, Orthopedics, Cardiology', 600, 180, 1, 1, 'approved'),

('Shaukat Khanum Memorial Cancer Hospital', 'SKMCH001', 'LIC006', 'Dr. Imran Ahmed', '+92-42-35905000', 'info@shaukatkhanum.org.pk', 'Johar Town, Lahore', 'Lahore', 'Punjab', '54000', 'Oncology, Laboratory, Radiology, Pharmacy', 'Oncology, Radiation Therapy, Nuclear Medicine', 300, 80, 1, 1, 'approved'),

('Mayo Hospital Lahore', 'MHL001', 'LIC007', 'Dr. Zainab Malik', '+92-42-99203171', 'mayo@mayo.edu.pk', 'Nila Gumbad Chowk, Lahore', 'Lahore', 'Punjab', '54000', 'Emergency, ICU, Laboratory, Blood Bank', 'General Medicine, Surgery, Pediatrics', 700, 210, 1, 1, 'approved'),

('Fatima Memorial Hospital', 'FMH001', 'LIC008', 'Dr. Omar Farooq', '+92-42-35300200', 'info@fmh.com.pk', 'Shadman, Lahore', 'Lahore', 'Punjab', '54000', 'Emergency, ICU, Laboratory, Cardiac Care', 'Cardiology, Neurology, Orthopedics', 250, 75, 1, 1, 'approved'),

-- Islamabad Hospitals
('Pakistan Institute of Medical Sciences (PIMS)', 'PIMS001', 'LIC009', 'Dr. Kashif Mahmood', '+92-51-9261170', 'info@pims.gov.pk', 'Shaheed Zulfiqar Ali Bhutto Medical University, Islamabad', 'Islamabad', 'Federal Capital Territory', '44000', 'Emergency, ICU, Laboratory, Trauma Center', 'General Medicine, Surgery, Neurology', 800, 240, 1, 1, 'approved'),

('Shifa International Hospital', 'SIH001', 'LIC010', 'Dr. Ayesha Rahman', '+92-51-8463463', 'info@shifa.com.pk', 'Pitras Bukhari Road, Islamabad', 'Islamabad', 'Federal Capital Territory', '44000', 'Emergency, ICU, Laboratory, Heart Center', 'Cardiology, Oncology, Orthopedics', 400, 120, 1, 1, 'approved'),

('Maroof International Hospital', 'MIH001', 'LIC011', 'Dr. Tariq Mehmood', '+92-51-8315001', 'info@maroof.com.pk', 'PWD Road, Islamabad', 'Islamabad', 'Federal Capital Territory', '44000', 'Emergency, ICU, Laboratory, Maternity', 'General Medicine, Pediatrics, Gynecology', 200, 60, 1, 1, 'approved'),

-- Rawalpindi Hospitals
('Armed Forces Institute of Cardiology', 'AFIC001', 'LIC012', 'Dr. Bilal Shah', '+92-51-9271011', 'info@afic.gov.pk', 'Tipu Road, Rawalpindi', 'Rawalpindi', 'Punjab', '46000', 'Cardiac Care, ICU, Laboratory, Emergency', 'Cardiology, Cardiac Surgery, Interventional Cardiology', 300, 90, 1, 1, 'approved'),

('Holy Family Hospital', 'HFH001', 'LIC013', 'Dr. Nadia Hussain', '+92-51-5556301', 'info@hfh.edu.pk', 'Satellite Town, Rawalpindi', 'Rawalpindi', 'Punjab', '46300', 'Emergency, ICU, Laboratory, Maternity', 'General Medicine, Surgery, Pediatrics', 500, 150, 1, 1, 'approved'),

-- Peshawar Hospitals
('Lady Reading Hospital', 'LRH001', 'LIC014', 'Dr. Saeed Khan', '+92-91-9211430', 'info@lrh.edu.pk', 'University Road, Peshawar', 'Peshawar', 'Khyber Pakhtunkhwa', '25000', 'Emergency, ICU, Laboratory, Burn Unit', 'General Medicine, Surgery, Neurology', 600, 180, 1, 1, 'approved'),

('Rehman Medical Institute', 'RMI001', 'LIC015', 'Dr. Amjad Ali', '+92-91-5838034', 'info@rmi.edu.pk', 'Phase 5, Hayatabad, Peshawar', 'Peshawar', 'Khyber Pakhtunkhwa', '25000', 'Emergency, ICU, Laboratory, Cardiac Care', 'Cardiology, Orthopedics, General Medicine', 300, 90, 1, 1, 'approved'),

-- Faisalabad Hospitals
('Allied Hospital Faisalabad', 'AHF001', 'LIC016', 'Dr. Rizwan Ahmed', '+92-41-9201083', 'info@allied.edu.pk', 'Sargodha Road, Faisalabad', 'Faisalabad', 'Punjab', '38000', 'Emergency, ICU, Laboratory, Blood Bank', 'General Medicine, Surgery, Pediatrics', 500, 150, 1, 1, 'approved'),

('District Headquarters Hospital', 'DHQ001', 'LIC017', 'Dr. Farhan Malik', '+92-41-2628395', 'dhq@health.punjab.gov.pk', 'Civil Lines, Faisalabad', 'Faisalabad', 'Punjab', '38000', 'Emergency, ICU, Laboratory, Maternity', 'General Medicine, Surgery, Gynecology', 400, 120, 1, 1, 'approved'),

-- Multan Hospitals
('Nishtar Hospital Multan', 'NHM001', 'LIC018', 'Dr. Shahid Mahmood', '+92-61-9200264', 'info@nishtar.edu.pk', 'Nishtar Road, Multan', 'Multan', 'Punjab', '60000', 'Emergency, ICU, Laboratory, Trauma Center', 'General Medicine, Surgery, Orthopedics', 800, 240, 1, 1, 'approved'),

('Multan Institute of Kidney Diseases', 'MIKD001', 'LIC019', 'Dr. Asma Riaz', '+92-61-6781234', 'info@mikd.org.pk', 'Bosan Road, Multan', 'Multan', 'Punjab', '60000', 'Dialysis, Laboratory, Emergency', 'Nephrology, General Medicine', 150, 45, 1, 1, 'approved'),

-- Quetta Hospitals
('Sandeman Provincial Hospital', 'SPH001', 'LIC020', 'Dr. Abdul Qadir', '+92-81-9202847', 'sph@health.balochistan.gov.pk', 'Jinnah Road, Quetta', 'Quetta', 'Balochistan', '87300', 'Emergency, ICU, Laboratory, Blood Bank', 'General Medicine, Surgery, Pediatrics', 400, 120, 1, 1, 'approved'),

('Bolan Medical Complex', 'BMC001', 'LIC021', 'Dr. Maryam Baloch', '+92-81-9202641', 'bmc@buitms.edu.pk', 'Saryab Road, Quetta', 'Quetta', 'Balochistan', '87300', 'Emergency, ICU, Laboratory, Maternity', 'General Medicine, Surgery, Gynecology', 300, 90, 1, 1, 'approved'),

-- Hyderabad Hospitals
('Liaquat University Hospital', 'LUH001', 'LIC022', 'Dr. Jameel Ahmed', '+92-22-2740172', 'info@lumhs.edu.pk', 'Jamshoro Road, Hyderabad', 'Hyderabad', 'Sindh', '71000', 'Emergency, ICU, Laboratory, Cardiac Care', 'Cardiology, General Medicine, Surgery', 500, 150, 1, 1, 'approved'),

-- Sialkot Hospitals
('Allama Iqbal Memorial Teaching Hospital', 'AIMTH001', 'LIC023', 'Dr. Waqas Ali', '+92-52-4591201', 'aimth@health.punjab.gov.pk', 'Labor Colony, Sialkot', 'Sialkot', 'Punjab', '51310', 'Emergency, ICU, Laboratory, Blood Bank', 'General Medicine, Surgery, Pediatrics', 350, 105, 1, 1, 'approved'),

-- Gujranwala Hospitals
('DHQ Hospital Gujranwala', 'DHQG001', 'LIC024', 'Dr. Faiza Nasir', '+92-55-3706201', 'dhq.gujranwala@health.punjab.gov.pk', 'GT Road, Gujranwala', 'Gujranwala', 'Punjab', '52250', 'Emergency, ICU, Laboratory, Maternity', 'General Medicine, Surgery, Gynecology', 400, 120, 1, 1, 'approved'),

-- Sargodha Hospitals
('DHQ Hospital Sargodha', 'DHQS001', 'LIC025', 'Dr. Hassan Raza', '+92-48-3702115', 'dhq.sargodha@health.punjab.gov.pk', 'Fatima Jinnah Road, Sargodha', 'Sargodha', 'Punjab', '40100', 'Emergency, ICU, Laboratory, Blood Bank', 'General Medicine, Surgery, Orthopedics', 300, 90, 1, 1, 'approved');

-- Update hospitals count
SELECT COUNT(*) as 'Total Hospitals Added' FROM hospitals WHERE approval_status = 'approved';

-- Show cities with hospital counts
SELECT city, state, COUNT(*) as hospital_count 
FROM hospitals 
WHERE approval_status = 'approved' 
GROUP BY city, state 
ORDER BY state, city;
