USE cxc55311;

CREATE TABLE User
(
    UserName VARCHAR(100) NOT NULL,
    FirstName VARCHAR(50) NOT NULL,
    LastName VARCHAR(50) NOT NULL,
    Email VARCHAR(100) NOT NULL,
    ContactNumber CHAR(12) NOT NULL,
    Password VARCHAR(100) NOT NULL,
    PRIMARY KEY(UserName)
);

CREATE TABLE Employer
(
    UserName VARCHAR(100) NOT NULL,
    EmployerName VARCHAR(100) NOT NULL,
    AccStatus BOOL NOT NULL,
    BALANCE DOUBLE(100,100) NOT NULL,
    PRIMARY KEY(UserName),
    FOREIGN KEY (UserName) REFERENCES User (UserName) ON DELETE CASCADE
);

CREATE TABLE Applicant
(
    UserName VARCHAR(100) NOT NULL,
    AccStatus BOOL NOT NULL,
    PRIMARY KEY(UserName),
    FOREIGN KEY (UserName) REFERENCES User (UserName) ON DELETE CASCADE
);

CREATE TABLE Admin
(
    UserName VARCHAR(100) NOT NULL,
    PRIMARY KEY(UserName),
    FOREIGN KEY (UserName) REFERENCES User (UserName) ON DELETE CASCADE
);

CREATE TABLE EmployerCategory
(
    Category VARCHAR(100), # enum prime, gold
    MonthlyCharge DOUBLE(100, 100),
    PRIMARY KEY(Category)
);

CREATE TABLE ApplicantCategory
(
    Category VARCHAR(100), # enum basic, prime, gold
    MonthlyCharge DOUBLE(100, 100),
    PRIMARY KEY(Category)
);

CREATE TABLE Job
(
    JobID INT,
    EmployerUserName VARCHAR(100),
    Title VARCHAR(100),
    DatePosted DATE,
    Description VARCHAR(50),
    Category VARCHAR(100),
    JobStatus BOOL, # open, closed (t, f)
    EmpNeeded INT,
    PRIMARY KEY(JobID),
    FOREIGN KEY (EmployerUserName) REFERENCES Employer (UserName)
);

#CREATE TABLE DefaultMOP
#(
 #   CardNumber VARCHAR(16),
#    Auto_Manual BOOL, #
#    PRIMARY KEY(CardNumber)
#);

CREATE TABLE CreditCardInfo
(
    CCNumber VARCHAR(16),
    ExpireDate DATE,
    UserName VARCHAR(100),
    CCBNumber VARCHAR(3),
    IsDefault BOOL,
    Auto_Manual BOOL,
    PRIMARY KEY(CCnumber, ExpireDate),
    FOREIGN KEY(UserName) REFERENCES User(UserName)
);

CREATE TABLE PADInfo
(
    AccountNumber VARCHAR(7),
    UserName VARCHAR(100),
    InstituteNumber VARCHAR(3),
    BranchNumber VARCHAR(3),
    IsDefault BOOL,
    Auto_Manual BOOL,
    PRIMARY KEY(AccountNumber),
    FOREIGN KEY(UserName) REFERENCES User(UserName)
);

CREATE TABLE ApplicantBalance
(
  ApplicantUserName VARCHAR(100),
  BALANCE DOUBLE(100,100),
  PRIMARY KEY(ApplicantUserName),
  FOREIGN KEY (ApplicantUserName) REFERENCES User(UserName)
);

#CREATE TABLE Payment
#(
#    CardNumber VARCHAR(16),
#    PaymentDate DATE,
#    Amount DOUBLE(100,100),
#   PRIMARY KEY (CardNumber, PaymentDate),
#    FOREIGN KEY (CardNumber) REFERENCES CreditCardInfo (CCNumber)
#);

# CREATE TABLE SelectEmployerCategory
# (
#     EmployerUserName VARCHAR(100),
#     StartDate DATE,
#     Charge Double(100,100),
#     EmployerCategory VARCHAR(100), # enum prime, gold
#     PRIMARY KEY (EmployerUserName, StartDate),
#     FOREIGN KEY (EmployerCategory) REFERENCES EmployerCategory(Category) ON DELETE CASCADE
# );

# CREATE TABLE SelectApplicantCategory
# (
#     ApplicantUserName VARCHAR(100),
#     StarDate DATE,
#     Charge DOUBLE(100,100),
#     ApplicantCategory VARCHAR(100),
#     PRIMARY KEY(ApplicantUserName, StarDate),
#     FOREIGN KEY (ApplicantCategory) REFERENCES ApplicantCategory(Category) ON DELETE CASCADE
# );

CREATE TABLE Application
(
    ApplicantUserName VARCHAR(100),
    JobID VARCHAR(100),
    ApplicationStatus VARCHAR(100), #enum: denied, review, sent, accepted, hired
    ApplicationDate DATE,
    PRIMARY KEY(ApplicantUserName, JobID),
    FOREIGN KEY (JobID) REFERENCES Job(JobID),
    FOREIGN KEY(ApplicantUserName) REFERENCES Applicant(UserName)
);