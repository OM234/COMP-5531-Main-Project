USE cxc55311;

CREATE TABLE User
(
    UserName VARCHAR(100),
    FirstName VARCHAR(50),
    LastName VARCHAR(50),
    Email VARCHAR(100),
    ContactNumber CHAR(12),
    Password VARCHAR(100),
    PRIMARY KEY(UserName)
);

CREATE TABLE Employer
(
    UserName VARCHAR(100),
    EmployerName VARCHAR(100),
    AccStatus VARCHAR(100),
    BALANCE DOUBLE(100,100),
    PRIMARY KEY(UserName)
);

CREATE TABLE Applicant
(
    UserName VARCHAR(100),
    AccStatus VARCHAR(100),
    PRIMARY KEY(UserName)
);

CREATE TABLE Admin
(
    UserName VARCHAR(100),
    PRIMARY KEY(UserName)
);

CREATE TABLE EmployerCategory
(
    Category VARCHAR(100),
    MonthlyCharge DOUBLE(100, 100),
    PRIMARY KEY(Category)
);

CREATE TABLE ApplicantCategory
(
    Category VARCHAR(100),
    MonthlyCharge DOUBLE(100, 100),
    PRIMARY KEY(Category)
);

CREATE TABLE Job
(
    JobID INT,
    EmployerUserName VARCHAR(100),
    Title VARCHAR(100),
    DatePosted DATE,
    Description Text,
    Category VARCHAR(100),
    JobStatus VARCHAR(100),
    EmpNeeded VARCHAR(100),
    PRIMARY KEY(JobID),
    FOREIGN KEY (EmployerUserName) REFERENCES Employer (UserName)
);

CREATE TABLE DefaultMOP
(
    CardNumber INT,
    Auto_Manual VARCHAR(100),
    PRIMARY KEY(CardNumber)
);

CREATE TABLE CreditCardInfo
(
    CCNumber BIGINT,
    ExpireDate DATE,
    UserUserName VARCHAR(100),
    CCBNumber BIGINT,
    PRIMARY KEY(CCnumber, ExpireDate),
    FOREIGN KEY(UserUserName) REFERENCES User(UserName)
);

CREATE TABLE PADInfo
(
    AccountNumber INT,
    UserUserName VARCHAR(100),
    InstituteNumber INT,
    BranchNumber INT,
    PRIMARY KEY(AccountNumber),
    FOREIGN KEY(UserUserName) REFERENCES User(UserName)
);

CREATE TABLE ApplicantBalance
(
  ApplicantUserName VARCHAR(100),
  BALANCE DOUBLE(100,100),
  PRIMARY KEY(ApplicantUserName),
  FOREIGN KEY (ApplicantUserName) REFERENCES User(UserName)
);

CREATE TABLE Payment
(
    CardNumber BIGINT,
    PaymentDate DATE,
    Amount DOUBLE(100,100),
    PRIMARY KEY (CardNumber, PaymentDate),
    FOREIGN KEY (CardNumber) REFERENCES CreditCardInfo (CCNumber)
);

CREATE TABLE SelectEmployerCategory
(
    EmployerUserName VARCHAR(100),
    StartDate DATE,
    Charge Double(100,100),
    EmployerCategory VARCHAR(100),
    PRIMARY KEY (EmployerUserName, StartDate),
    FOREIGN KEY (EmployerCategory) REFERENCES EmployerCategory(Category) ON DELETE CASCADE
);

CREATE TABLE SelectApplicantCategory
(
    ApplicantUserName VARCHAR(100),
    StarDate DATE,
    Charge DOUBLE(100,100),
    ApplicantCategory VARCHAR(100),
    PRIMARY KEY(ApplicantUserName, StarDate),
    FOREIGN KEY (ApplicantCategory) REFERENCES ApplicantCategory(Category) ON DELETE CASCADE
);

CREATE TABLE Apply
(
    ApplicantUserName VARCHAR(100),
    JobID INT,
    ApplicationStatus VARCHAR(100),
    ApplicationDate DATE,
    PRIMARY KEY(ApplicantUserName, JobID),
    FOREIGN KEY (JobID) REFERENCES Job(JobID)
);