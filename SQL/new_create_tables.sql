create table user
(
    UserName      varchar(30) not null
        primary key,
    FirstName     varchar(30) not null,
    LastName      varchar(30) not null,
    Email         varchar(50) not null,
    ContactNumber char(12)    not null,
    Password      varchar(50) not null
);

create table padinfo
(
    AccountNumber   varchar(7) not null
        primary key,
    InstituteNumber varchar(3) ,
    BranchNumber    varchar(5) ,
    IsDefault       tinyint(1) ,
    Auto_Manual     tinyint(1)
);

create table creditcardinfo
(
    CCNumber    varchar(16) not null,
    ExpireDate  date        not null,
    CCBNumber   varchar(3)  ,
    IsDefault   tinyint(1)  ,
    Auto_Manual tinyint(1)  ,
    primary key (CCNumber, ExpireDate)
);

create table employer
(
    UserName     varchar(30)            not null
        primary key,
    EmployerName varchar(100)           not null,
    Category     enum ('prime', 'gold') ,
    Balance      decimal(10, 2)         not null,
    constraint employer_ibfk_1
        foreign key (UserName) references user (UserName)
            on delete cascade
);

create table applicant
(
    UserName varchar(30)                     not null
        primary key,
    Category enum ('basic', 'prime', 'gold') ,
    Balance  decimal(10, 2)                  not null,
    constraint applicant_ibfk_1
        foreign key (UserName) references user (UserName)
            on update cascade on delete cascade
);

create table admin
(
    UserName varchar(30) not null
        primary key,
    constraint admin_ibfk_1
        foreign key (UserName) references user (UserName)
            on delete cascade
);


create table job
(
    JobID            int auto_increment primary key,
    EmployerUserName varchar(30) ,
    Title            varchar(50) ,
    DatePosted       date        ,
    Description      varchar(50) ,
    Category         varchar(50) ,
    JobStatus        tinyint(1)  ,
    EmpNeeded        int         ,
    constraint job_ibfk_1
        foreign key (EmployerUserName) references employer (UserName)
            on update cascade on delete cascade
);

create table application
(
    ApplicantUserName varchar(30)                                            not null,
    JobID             int                                                    not null,
    ApplicationStatus enum ('denied', 'review', 'sent', 'accepted', 'hired') ,
    ApplicationDate   date                                                   ,
    primary key (ApplicantUserName, JobID),
    constraint application_ibfk_2
        foreign key (ApplicantUserName) references applicant (UserName)
            on update cascade on delete cascade,
    constraint application_job_JobID_fk
        foreign key (JobID) references job (JobID)
            on update cascade on delete cascade
);

create table employerpad
(
    EmployerUserName varchar(30) ,
    AccountNumber    varchar(7)  not null
        primary key,
    constraint employerpad_ibfk_1
        foreign key (EmployerUserName) references employer (UserName)
            on delete cascade,
    constraint employerpad_ibfk_2
        foreign key (AccountNumber) references padinfo (AccountNumber)
            on update cascade on delete cascade
);

create index EmployerUserName
    on employerpad (EmployerUserName);


create table employercc
(
    EmployerUserName varchar(30) ,
    CCNumber         varchar(16) not null
        primary key,
    constraint employercc_ibfk_1
        foreign key (EmployerUserName) references employer (UserName)
            on delete cascade,
    constraint employercc_ibfk_2
        foreign key (CCNumber) references creditcardinfo (CCNumber)
            on update cascade on delete cascade
);

create index EmployerUserName
    on employercc (EmployerUserName);


create table applicantcc
(
    ApplicantUserName varchar(30) ,
    CCNumber          varchar(16) not null
        primary key,
    constraint applicantcc_ibfk_1
        foreign key (ApplicantUserName) references applicant (UserName)
            on delete cascade,
    constraint applicantcc_ibfk_2
        foreign key (CCNumber) references creditcardinfo (CCNumber)
            on update cascade on delete cascade
);

create index ApplicantUserName
    on applicantcc (ApplicantUserName);


create table applicantpad
(
    ApplicantUserName varchar(30) ,
    AccountNumber     varchar(7)  not null
        primary key,
    constraint applicantpad_ibfk_1
        foreign key (ApplicantUserName) references applicant (UserName)
            on delete cascade,
    constraint applicantpad_ibfk_2
        foreign key (AccountNumber) references padinfo (AccountNumber)
            on update cascade on delete cascade
);

create index ApplicantUserName
    on applicantpad (ApplicantUserName);
