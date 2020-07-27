use cxc55311;

CREATE TABLE User
(
    UserName VARCHAR(30) NOT NULL,
    FirstName VARCHAR(30) NOT NULL,
    LastName VARCHAR(30) NOT NULL,
    Email VARCHAR(50) NOT NULL,
    ContactNumber CHAR(12) NOT NULL,
    Password VARCHAR(50) NOT NULL,
    PRIMARY KEY(UserName)
);

CREATE TABLE Employer
(
    UserName VARCHAR(30) NOT NULL,
    EmployerName VARCHAR(100) NOT NULL,
    AccStatus BOOL NOT NULL, # activated / deactivated
    Category ENUM('prime', 'gold'),
    Balance DECIMAL(10,2) NOT NULL,
    PRIMARY KEY(UserName),
    FOREIGN KEY (UserName) REFERENCES User (UserName) ON DELETE CASCADE
);

CREATE TABLE Applicant
(
    UserName VARCHAR(30) NOT NULL,
    AccStatus BOOL NOT NULL, # activated / deactivated
    Category ENUM('basic', 'prime', 'gold'),
    Balance DECIMAL(10,2) NOT NULL,
    PRIMARY KEY(UserName),
    FOREIGN KEY (UserName) REFERENCES User (UserName) ON DELETE CASCADE
);

CREATE TABLE Admin
(
    UserName VARCHAR(30) NOT NULL,
    PRIMARY KEY(UserName),
    FOREIGN KEY (UserName) REFERENCES User (UserName) ON DELETE CASCADE
);

CREATE TABLE Job
(
    JobID INT,
    EmployerUserName VARCHAR(30),
    Title VARCHAR(50),
    DatePosted DATE,
    Description VARCHAR(50),
    Category VARCHAR(50),
    JobStatus BOOL, # open, closed (t, f)
    EmpNeeded INT, # Positions to be filled
    PRIMARY KEY(JobID),
    FOREIGN KEY (EmployerUserName) REFERENCES Employer (UserName)
);

CREATE TABLE CreditCardInfo
(
    CCNumber VARCHAR(16),
    ExpireDate DATE,
    CCBNumber VARCHAR(3),
    IsDefault BOOL,
    Auto_Manual BOOL,
    PRIMARY KEY(CCnumber, ExpireDate)
);

CREATE TABLE PADInfo
(
    AccountNumber VARCHAR(7),
    InstituteNumber VARCHAR(3),
    BranchNumber VARCHAR(3),
    IsDefault BOOL,
    Auto_Manual BOOL,
    PRIMARY KEY(AccountNumber)
);

CREATE TABLE Application
(
    ApplicantUserName VARCHAR(30),
    JobID INT,
    ApplicationStatus ENUM('denied', 'review', 'sent', 'accepted', 'hired'),
    ApplicationDate DATE,
    PRIMARY KEY(ApplicantUserName, JobID),
    FOREIGN KEY (JobID) REFERENCES Job(JobID),
    FOREIGN KEY(ApplicantUserName) REFERENCES Applicant(UserName)
);

CREATE TABLE EmployerCC
(
  EmployerUserName VARCHAR(30),
  CCNumber VARCHAR(16),
  PRIMARY KEY(EmployerUserName, CCNumber),
  FOREIGN KEY(EmployerUserName) REFERENCES Employer(UserName) ON DELETE CASCADE,
  FOREIGN KEY(CCNumber) REFERENCES CreditCardInfo(CCNumber) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE EmployerPAD
(
    EmployerUserName VARCHAR(30),
    AccountNumber VARCHAR(7),
    PRIMARY KEY (EmployerUserName, AccountNumber),
    FOREIGN KEY (EmployerUserName) REFERENCES Employer(UserName) ON DELETE CASCADE,
    FOREIGN KEY(AccountNumber) REFERENCES PADInfo(AccountNumber) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE ApplicantCC
(
  ApplicantUserName VARCHAR(30),
  CCNumber VARCHAR(16),
  PRIMARY KEY(ApplicantUserName, CCNumber),
  FOREIGN KEY(ApplicantUserName) REFERENCES Applicant(UserName) ON DELETE CASCADE,
  FOREIGN KEY (CCNumber) REFERENCES CreditCardInfo(CCNumber) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE ApplicantPAD
(
  ApplicantUserName VARCHAR(30),
  AccountNumber VARCHAR(7),
  PRIMARY KEY(ApplicantUserName, AccountNumber),
  FOREIGN KEY (ApplicantUserName) REFERENCES Applicant(UserName) ON DELETE CASCADE,
  FOREIGN KEY(AccountNumber) REFERENCES PADInfo(AccountNumber) ON DELETE CASCADE ON UPDATE CASCADE
);
