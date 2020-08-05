use cxc55311;

# i. Create, delete, edit, and display an Employer

INSERT INTO User(UserName, FirstName, LastName, Email, ContactNumber, Password)
VALUES ('Xavier_K67', 'Xavier', 'Kelp', 'XavierKelp34@coldmail.com', '514-675-2345', 'xylophone454');

INSERT INTO Employer(UserName, EmployerName, Activated, Category, Balance)
VALUES ('Xavier_K67', 'Microsoft Corporation', true, 'gold', '675.23');

DELETE FROM Employer
WHERE UserName = 'Xavier_K67';

UPDATE Employer
SET EmployerName = 'Google LLC'
WHERE UserName = 'Lennon_R37';

# Display specific record
SELECT *
FROM Employer
WHERE UserName = 'Lennon_R37';

# Display random record
SELECT *
FROM Employer
ORDER BY RAND()
LIMIT 1;

# ii. Create, delete, edit, display a category by an Employer.

INSERT INTO User(UserName, FirstName, LastName, Email, ContactNumber, Password)
VALUES ('Xavier_K67', 'Xavier', 'Kelp', 'XavierKelp34@coldmail.com', '514-675-2345', 'xylophone454');

INSERT INTO Employer(UserName, EmployerName, Activated, Category, Balance)
VALUES ('Xavier_K67', 'Microsoft Corporation', true, 'prime', '675.23');

UPDATE Employer
SET Category = null
WHERE UserName = 'Harley_O96';

UPDATE Employer
SET Category = 'gold'
WHERE UserName = 'Harley_O96';

SELECT Category, EmployerName
FROM Employer
ORDER BY RAND()
LIMIT 1;

# iii. Post a new job by an employer

INSERT INTO Job(JOBID, EMPLOYERUSERNAME, TITLE, DATEPOSTED, DESCRIPTION, CATEGORY, JOBSTATUS, EMPNEEDED)
VALUES ('1111', 'Alexis_S04', 'Project Manager', '2020-07-20', 'Looking for reliable person. 2 years experience',
        'Information Technology', '1', '1');

# iv. Provide a job offer for an employee by an employer

INSERT INTO Application(ApplicantUserName, JobID, ApplicationStatus, ApplicationDate)
VALUES ('Addison_C81', '1111', 'sent', '2020-07-23');

# v. Report of a posted job by an employer

SELECT Title, Description, DatePosted, ApplicantUserName, ApplicationStatus
FROM application, job
WHERE application.JobID = job.JobID AND application.JobID = 1743;

# vi. Report of posted jobs by an employer during a specific period of time

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

# vii. Create/Delete/Edit/Display an employee

INSERT INTO User(UserName, FirstName, LastName, Email, ContactNumber, Password)
VALUES ('Zack_B67', 'Zack', 'Bael', 'ZackBael61@hmail.com', '438-634-9835', 'couch872');

INSERT INTO Applicant(UserName, Activated, Category, Balance)
VALUES ('Zack_B67', true, 'basic', '34.55');

DELETE FROM applicant
WHERE UserName = 'Zack_B67';

UPDATE Applicant
SET BALANCE = '100.34'
Where UserName = 'Ainsley_L20';

# Display specific record
SELECT *
FROM applicant
WHERE UserName = 'Ainsley_L20';

# Display random record
SELECT *
FROM applicant
ORDER BY RAND()
LIMIT 1;

# viii. Search for a job by an employee (or search for jobs an employee applied for?)

SELECT *
FROM Job;

# List of jobs by specific category.
SELECT *
FROM job
WHERE Category = 'Education and Training';

SELECT *
FROM application
WHERE ApplicantUserName = 'Alex_G66';

# ix. Apply for a job by an employee.

INSERT INTO application(applicantusername, jobid, applicationstatus, applicationdate)
VALUES ('Campbell_B00', '1426', 'sent', '2020-07-25');

# x. Accept/Deny a job offer by an employee.

UPDATE application
SET ApplicationStatus = 'accepted'
WHERE ApplicantUserName = 'Campbell_B00' AND JobID = '1426';

UPDATE application
SET ApplicationStatus = 'denied'
WHERE ApplicantUserName = 'Campbell_B00' AND JobID = '1426';

# xi. Withdraw from an applied job by an employee

DELETE from application
WHERE ApplicantUserName = 'Campbell_B00' AND JobID = '1426';

# xii. Delete a profile by an employee.

DELETE FROM application
WHERE ApplicantUserName = 'Campbell_B00';

# xiii. Report of applied jobs by an employee during a specific period of time

SELECT job.Title, application.ApplicationDate, job.description, application.ApplicationStatus
FROM application, job
WHERE (ApplicationDate BETWEEN '2020-01-20' AND '2020-10-15') AND job.JobID = application.JobID AND
      applicantUserName = 'Bethany_Delena72';

# xiv. Add/Delete/Edit a method of payment by a user.

INSERT INTO creditcardinfo(CCNumber, ExpireDate, CCBNumber, IsDefault, Auto_Manual)
VALUES('3468567234567502', '2025-02-22', '345', '1', '0');

INSERT INTO applicantcc(ApplicantUserName, CCNumber)
VALUES('Frances_M45', '3468567234567502');

DELETE FROM creditcardinfo
WHERE CCNUMBER IN (SELECT applicantcc.CCnumber FROM applicantcc WHERE CCNumber = '3468567234567502' AND
                                                                      ApplicantUserName = 'Frances_M45');

INSERT INTO padinfo(AccountNumber, InstituteNumber, BranchNumber, IsDefault, Auto_Manual)
VALUES('1895612', '001', '232', '0', '0');

INSERT INTO applicantpad(ApplicantUserName, AccountNumber)
VALUES('Frances_M45', '1895612');

DELETE FROM padinfo
WHERE AccountNumber IN (SELECT applicantpad.accountnumber FROM applicantpad WHERE applicantpad.AccountNumber ='1895612'
                        AND applicantpad.ApplicantUserName = 'Frances_M45');

# Update credit card in this creditcardinfo table first, then update in child table applicantcc
UPDATE creditcardinfo
SET CCnumber = '0729363628516973'
WHERE CCNumber IN (SELECT applicantcc.CCNumber FROM applicantcc WHERE ApplicantUserName = 'Frances_M45');

UPDATE applicantcc
SET CCnumber = '0729363628516973'
WHERE ApplicantUserName = 'Frances_M45';

# 0729363628516973 original cc

# xv. Add/Delete/Edit an automatic payment by a user.

INSERT INTO creditcardinfo(CCNumber, ExpireDate, CCBNumber, IsDefault, Auto_Manual)
VALUES ('827390457394827812', '2026-03-24', '323', '0', '1');

INSERT INTO applicantcc(ApplicantUserName, CCNumber)
VALUES ('Frances_M45', '827390457394827812');

DELETE from applicantcc
WHERE ApplicantUserName = 'Frances_M45';

# Trying to update an auto (1) credit card from a user... But usernames aren't in the same table as auto setting.
UPDATE applicantcc
SET CCNUMBER = '827390457394827812'
WHERE CCNumber IN (SELECT creditcardinfo.CCNumber FROM creditcardinfo WHERE Auto_Manual = '1' AND
                                                                            CCNumber = '0729363628516973');

# xvi. Make a manual payment by a user.

# Prime payment
UPDATE applicant
SET Balance = Balance - 10
WHERE UserName = 'Frances_M45';

# Gold Payment
UPDATE applicant
SET Balance = Balance - 20
WHERE UserName = 'Campbell_B00';

# Prime payment
UPDATE applicant
SET Balance = Balance - 50
WHERE UserName = 'Amari_C79';

# Gold Payment
UPDATE applicant
SET Balance = Balance - 100
WHERE UserName = 'Lennon_R94';

# xvii. Report of all users by the administrator for employers or employees

SELECT UserName, FirstName, LastName, Email, Category, Balance
FROM user natural join applicant
UNION
SELECT UserName, FirstName, LastName, Email, Category, Balance
FROM user natural join employer;

# xviii. Report of all outstanding balance accounts

SELECT UserName, email, balance
FROM user
natural join applicant
WHERE balance < 0
UNION
SELECT UserName, email, Balance
from user
natural join employer
WHERE Balance < 0;
