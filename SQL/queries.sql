use cxc55311;

# i. Create, delete, edit, and display an Employer

# username of employer is foreign key to user table. insert into user table first.
INSERT INTO user(UserName, FirstName, LastName, Email, ContactNumber, Password)
VALUES ('Xavier_Kyle67', 'Xavier', 'Kelp', 'XavierKelp34@coldmail.com', '514-675-2345', 'xylophone454');

INSERT INTO employer(UserName, EmployerName, Activated, Category, Balance)
VALUES ('Xavier_Kyle67', 'Microsoft Corporation', true, 'gold', '675.23');

DELETE FROM employer
WHERE UserName = 'Xavier_Kyle67';

UPDATE employer
SET EmployerName = 'Google LLC'
WHERE UserName = 'Fern_Jama67';

# Display specific employer record
SELECT *
FROM employer
WHERE UserName = 'Fern_Jama67';

# Display random emplyoer record
SELECT *
FROM employer
ORDER BY RAND()
LIMIT 1;

# ii. Create, delete, edit, display a category by an Employer.

INSERT INTO user(UserName, FirstName, LastName, Email, ContactNumber, Password)
VALUES ('Xavier_K67', 'Xavier', 'Kelp', 'XavierKelp34@coldmail.com', '514-675-2345', 'xylophone454');

INSERT INTO employer(UserName, EmployerName, Activated, Category, Balance)
VALUES ('Xavier_K67', 'Microsoft Corporation', true, 'prime', '675.23');

UPDATE employer
SET Category = null
WHERE UserName = 'Fern_Jama67';

UPDATE employer
SET Category = 'gold'
WHERE UserName = 'Fern_Jama67';

# Display a category and employer name of a random employer
SELECT Category, EmployerName
FROM employer
ORDER BY RAND()
LIMIT 1;

# iii. Post a new job by an employer

INSERT INTO job(JOBID, EMPLOYERUSERNAME, TITLE, DATEPOSTED, DESCRIPTION, CATEGORY, JOBSTATUS, EMPNEEDED)
VALUES ('1111', 'Kortez_Nena32', 'Project Manager', '2020-07-20', 'Looking for reliable person. 2 years experience',
        'Information Technology', '1', '1');

# iv. Provide a job offer for an employee by an employer

INSERT INTO application(ApplicantUserName, JobID, ApplicationStatus, ApplicationDate)
VALUES ('Ameisha_Dalesia62', '1111', 'sent', '2020-07-23');

# v. Report of a posted job by an employer (Job title and description, date
# posted, list of employees applied to the job and status of each application)

SELECT Title, DatePosted, description, j.Category, EmpNeeded, EmployerName, ApplicantUserName
    FROM employer e join job j on e.UserName = j.EmployerUserName join application
    WHERE EmployerName = 'Ultimate Software' and title = 'Global Mobility Specialist' and j.JobID = application.JobID;

# vi. Report of posted jobs by an employer during a specific period of time
# (Job title, date posted, short description of the job up to 50 characters, number
# of needed employees to the post, number of applied jobs to the post,
# number of accepted offers)

SELECT Job_Title, DatePosted, description, EmpNeeded, EmployerName, NumberHired, COUNT(Job_Title) as NumberOfApplicants
FROM (SELECT Title as Job_Title, DatePosted, description, EmpNeeded, EmployerName
    FROM employer e join job j on e.UserName = j.EmployerUserName join application
    WHERE EmployerName = 'Ultimate Software' AND (DatePosted BETWEEN '2020-01-26' AND '2020-11-08') and
          application.JobID = j.JobID) as
UltimateSoftwareJobs
join
(SELECT a.Title as HireTitle, COUNT(ApplicationStatus) AS NumberHired
FROM (SELECT Title FROM
    employer e join job j on e.UserName = j.EmployerUserName join application
    WHERE EmployerName = 'Ultimate Software' AND (DatePosted BETWEEN '2020-01-26' AND '2020-11-08') and
          application.JobID = j.JobID GROUP BY Title) as a
LEFT JOIN (SELECT Title, ApplicationStatus FROM employer e join job j on e.UserName = j.EmployerUserName join application
    WHERE EmployerName = 'Ultimate Software' AND (DatePosted BETWEEN '2020-01-26' AND '2020-11-08') and
          application.JobID = j.JobID) as b on a.Title = b.Title and b.ApplicationStatus = 'hired' GROUP BY
          a.Title) as
numberOfHires where UltimateSoftwareJobs.Job_Title = numberOfHires.HireTitle
GROUP BY Job_Title;

# vii. Create/Delete/Edit/Display an employee (job seeker)

INSERT INTO user(UserName, FirstName, LastName, Email, ContactNumber, Password)
VALUES ('Zack_Barns67', 'Zack', 'Bael', 'ZackBael61@hmail.com', '438-634-9835', 'couch872');

INSERT INTO applicant(UserName, Activated, Category, Balance)
VALUES ('Zack_Barns67', true, 'basic', '34.55');

DELETE FROM applicant
WHERE UserName = 'Zack_Barns67';

# edit an employee, changing their balance. Replace SET BALANCE' with any other attribute.
UPDATE applicant
SET BALANCE = '100.34'
Where UserName = 'Alizabeth_Carnell12';

# Display specific record
SELECT *
FROM applicant
WHERE UserName = 'Alizabeth_Carnell12';

# Display random record
SELECT *
FROM applicant
ORDER BY RAND()
LIMIT 1;

# viii. Search for a job by an employee (i.e. someone who wants a job searches for one)

#search for a job with a keyword. In this case, retrieves all 'technician' jobs'
SELECT *
FROM job
WHERE Title LIKE '%technician%';

# List of jobs by specific category.
SELECT *
FROM job
WHERE Category = 'Education and Training';

# ix. Apply for a job by an employee.

INSERT INTO application(applicantusername, jobid, applicationstatus, applicationdate)
VALUES ('Darien_Tzipporah02', '1145', 'sent', '2020-07-25');

# x. Accept/Deny a job offer by an employee.

UPDATE application
SET ApplicationStatus = 'accepted'
WHERE ApplicantUserName = 'Darien_Tzipporah02' AND JobID = '1145';

UPDATE application
SET ApplicationStatus = 'denied'
WHERE ApplicantUserName = 'Darien_Tzipporah02' AND JobID = '1145';

# xi. Withdraw from an applied job by an employee

DELETE from application
WHERE ApplicantUserName = 'Darien_Tzipporah02' AND JobID = '1145';

# xii. Delete a profile by an employee.

DELETE FROM application
WHERE ApplicantUserName = 'Darien_Tzipporah02';

# xiii. Report of applied jobs by an employee during a specific period of time (Job title, date applied,
# short description of the job up to 50 characters, status of the application).

SELECT job.Title, application.ApplicationDate, job.description, application.ApplicationStatus
FROM application, job
WHERE (ApplicationDate BETWEEN '2020-01-20' AND '2020-10-15') AND job.JobID = application.JobID AND
      applicantUserName = 'Ladarious_Nelia48';

# xiv. Add/Delete/Edit a method of payment by a user.

INSERT INTO creditcardinfo(CCNumber, ExpireDate, CCBNumber, IsDefault, Auto_Manual)
VALUES('3468567234567502', '2025-02-22', '345', '1', '0');

INSERT INTO applicantcc(ApplicantUserName, CCNumber)
VALUES('Ladarious_Nelia48', '3468567234567502');

DELETE FROM creditcardinfo
WHERE CCNUMBER IN (SELECT applicantcc.CCnumber FROM applicantcc WHERE CCNumber = '3468567234567502' AND
                                                                      ApplicantUserName = 'Ladarious_Nelia48');

INSERT INTO padinfo(AccountNumber, InstituteNumber, BranchNumber, IsDefault, Auto_Manual)
VALUES('1895612', '001', '232', '0', '0');

INSERT INTO applicantpad(ApplicantUserName, AccountNumber)
VALUES('Ladarious_Nelia48', '1895612');

DELETE FROM padinfo
WHERE AccountNumber IN (SELECT applicantpad.accountnumber FROM applicantpad WHERE applicantpad.AccountNumber ='1895612'
                        AND applicantpad.ApplicantUserName = 'Ladarious_Nelia48');

# Update credit card in this creditcardinfo table first, then update in child table applicantcc
UPDATE creditcardinfo
SET CCnumber = '0729363628516973'
WHERE CCNumber IN (SELECT applicantcc.CCNumber FROM applicantcc WHERE ApplicantUserName = 'Ladarious_Nelia48');

UPDATE applicantcc
SET CCnumber = '0729363628516973'
WHERE ApplicantUserName = 'Ladarious_Nelia48';

# xv. Add/Delete/Edit an automatic payment by a user.

INSERT INTO creditcardinfo(CCNumber, ExpireDate, CCBNumber, IsDefault, Auto_Manual)
VALUES ('827390457394827812', '2026-03-24', '323', '0', '1');

INSERT INTO applicantcc(ApplicantUserName, CCNumber)
VALUES ('Ladarious_Nelia48', '827390457394827812');

DELETE from applicantcc
WHERE ApplicantUserName = 'Ladarious_Nelia48';

UPDATE applicantcc
SET CCNUMBER = '827390457394827812'
WHERE CCNumber IN (SELECT creditcardinfo.CCNumber FROM creditcardinfo WHERE Auto_Manual = '1' AND
                                                                            CCNumber = '0729363628516973');

# xvi. Make a manual payment by a user.

# Prime Job Seeker payment
UPDATE applicant
SET Balance = Balance - 10
WHERE UserName = 'Ladarious_Nelia48';

# Gold Job Seeker Payment
UPDATE applicant
SET Balance = Balance - 20
WHERE UserName = 'Maigen_Cheryll33';

# Prime Employer payment
UPDATE applicant
SET Balance = Balance - 50
WHERE UserName = 'Telia_Naticia93';

# Gold Employer Payment
UPDATE applicant
SET Balance = Balance - 100
WHERE UserName = 'Venetia_Caspar38';

# xvii. Report of all users by the administrator for employers or employees (Name, email, category, status, balance).

SELECT FirstName, LastName, Email, Category, Activated as Status, Balance
FROM user natural join applicant
UNION
SELECT FirstName, LastName, Email, Category, Activated as Status, Balance
FROM user natural join employer;

# xviii. Report of all outstanding balance accounts (User name, email, balance,
# since when the account is suffering).

SELECT UserName, email, balance
FROM user
natural join applicant
WHERE balance < 0
UNION
SELECT UserName, email, Balance
from user
natural join employer
WHERE Balance < 0;
