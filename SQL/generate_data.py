import random
import re

class AnEmployer:
    def __init__(self, username, password, firstname, lastname, phnumber, empname, balance, acategory, email):
        self.username = username
        self.password = password
        self.firstname = firstname
        self.lastname = lastname
        self.phnumber = phnumber
        self.empname = empname
        self.balance = balance
        self.acategory = acategory
        self.email = email

class ASeeker:
    def __init__(self, username, password, firstname, lastname, phnumber, balance, acategory, email):
        self.username = username
        self.password = password
        self.firstname = firstname
        self.lastname = lastname
        self.phnumber = phnumber
        self.balance = balance
        self.category = acategory
        self.email = email

class AnAdmin:
    def __init__(self, username, password, firstname, lastname, phnumber, email):
        self.username = username
        self.password = password
        self.firstname = firstname
        self.lastname = lastname
        self.phnumber = phnumber
        self.email = email

class Application:
    def __init__(self, userName, appstatus, appdate):
        self.appUserName = userName
        self.appstatus = appstatus
        self.appdate = appdate

class AJob:
    def __init__(self, jobID, empUserName, title, date, description, category, status, empNeeded, listOfApp):
        self.jobID = jobID
        self.empUserName = empUserName
        self.title = title
        self.date = date
        self.description = description
        self.category = category
        self.status = status
        self.empNeeded = empNeeded
        self.listOfApp = listOfApp

class CreditCard:
    def __init__(self, CCNumber, expDate, CCB, isDefault, auto_manual):
        self.CCNumber = CCNumber
        self.expDate = expDate
        self.CCB = CCB
        self.isDefault = 0
        self.auto_manual = 0

class PAD:
    def __init__(self, accNum, instNum, branch, isDefault):
        self.accNum = accNum
        self.instNum = instNum
        self.branch = branch
        self.isDefault = 0
        self.auto_manual = 0


numEmployers = 100
numAdmins = 10
numSeekers = 500
numJobs = numSeekers * 5
numCreditCards = (numEmployers + numSeekers) * 5
numPAD = (numEmployers + numSeekers) * 5
numUsers = numEmployers + numAdmins + numSeekers
listOfEmployers = []
listOfSeekers = []
listOfAdmins = []
listOfJobs = []
listOfCreditCards = []
listOfPADs = []
userCreditCards = {}
userPADs = {}
phoneNumberList = []
userNameList = []
r = random

def createusers():
    for i in range(numEmployers):
        anEmployer = AnEmployer('', '', '', '', '', '', '', '', '')
        listOfEmployers.append(anEmployer)

    for i in range(numSeekers):
        aSeeker = ASeeker('', '', '', '', '', '', '', '')
        listOfSeekers.append(aSeeker)

    for i in range(numAdmins):
        anAdmin = AnAdmin('', '', '', '', '', '')
        listOfAdmins.append(anAdmin)


def makePhoneNumbers():
    # Phone Numbers
    numRegex1 = re.compile('^\\d\\d\\d$')
    numRegex2 = re.compile('^\\d\\d\\d-\\d\\d\\d$')

    areaCodeList = ['514', '438', '450']
    phoneNumber = ''

    for x in range(numUsers * 2):
        phoneNumber += areaCodeList[r.randrange(3)]
        for y in range(7):
            if numRegex1.match(phoneNumber) or numRegex2.match(phoneNumber):
                phoneNumber += "-" + str(r.randrange(10))
            else:
                phoneNumber += str(r.randrange(10))
        phoneNumberList.append(phoneNumber)
        #    print(phoneNumberList[x])
        phoneNumber = ''

def associatePhNumbersWithUsers() :
    for i in range(len(listOfEmployers)):
        listOfEmployers[i].phnumber = phoneNumberList[r.randrange(len(phoneNumberList))]
    for i in range(len(listOfSeekers)):
        listOfSeekers[i].phnumber = phoneNumberList[r.randrange(len(phoneNumberList))]
    for i in range(len(listOfAdmins)):
        listOfAdmins[i].phnumber = phoneNumberList[r.randrange(len(phoneNumberList))]

def setFirstAndLastNames():

    firstNameList = []
    lastNameList = []

    # First Names, Last Names
    with open('names.txt', 'r') as nameFile:
        for line in nameFile:
            firstNameList.append(line.strip())

    # for i in range(len(firstNameList)):
    #     print(firstNameList[i])

    with open('lastnames.txt', 'r') as lastNameFile:
        for line in lastNameFile:
            lastNameList.append(line.strip())

    for i in range(len(listOfEmployers)):
        listOfEmployers[i].firstname = firstNameList[r.randrange(len(firstNameList))]
        listOfEmployers[i].lastname = lastNameList[r.randrange(len(lastNameList))]

    for i in range(len(listOfSeekers)):
        listOfSeekers[i].firstname = firstNameList[r.randrange(len(firstNameList))]
        listOfSeekers[i].lastname = lastNameList[r.randrange(len(lastNameList))]

    for i in range(len(listOfAdmins)):
        listOfAdmins[i].firstname = firstNameList[r.randrange(len(firstNameList))]
        listOfAdmins[i].lastname = lastNameList[r.randrange(len(lastNameList))]
    # for i in range(len(lastNameList)):
    #     lastNameList[i] = lastNameList[i].lower()
    #     lastNameList[i] = lastNameList[i].capitalize()
    #    print(lastNameList[i])

def setEmails():
    # Emails
    emailDomain = ['coldmail.com', 'hmail.ca', 'zahoo.com']

    for i in range(len(listOfEmployers)):
        emailString = ""
        emailString += listOfEmployers[i].firstname + listOfEmployers[i].lastname + str(r.randrange(10)) + str(r.randrange(10)) + "@" + \
                       emailDomain[r.randrange(2)]
        listOfEmployers[i].email = emailString

    for i in range(len(listOfSeekers)):
        emailString = ""
        emailString += listOfSeekers[i].firstname + listOfSeekers[i].lastname + str(r.randrange(10)) + str(r.randrange(10)) + "@" + \
                       emailDomain[r.randrange(2)]
        listOfSeekers[i].email = emailString

    for i in range(len(listOfAdmins)):
        emailString = ""
        emailString += listOfAdmins[i].firstname + listOfAdmins[i].lastname + str(r.randrange(10)) + str(r.randrange(10)) + "@" + \
                       emailDomain[r.randrange(2)]
        listOfAdmins[i].email = emailString

    # for each in emailList:
    #    print(each)

def setUserNames():
    # Usernames

    for i in range(len(listOfEmployers)):
        while True:
            userString = ""
            userString += listOfEmployers[i].firstname + "_" + listOfEmployers[i].lastname + str(r.randrange(10)) + str(r.randrange(10))
            if userString not in userNameList:
                userNameList.append(userString)
                listOfEmployers[i].username = userString
                break

    for i in range(len(listOfSeekers)):
        while True:
            userString = ""
            userString += listOfSeekers[i].firstname + "_" + listOfSeekers[i].lastname + str(r.randrange(10)) + str(r.randrange(10))
            if userString not in userNameList:
                userNameList.append(userString)
                listOfSeekers[i].username = userString
                break

    for i in range(len(listOfAdmins)):
        while True:
            userString = ""
            userString += listOfAdmins[i].firstname + "_" + listOfAdmins[i].lastname + str(r.randrange(10)) + str(r.randrange(10))
            if userString not in userNameList:
                listOfAdmins[i].username = userString
                userNameList.append(userString)
                break
    # for each in userNameList:
    #     print(each)

def setPasswords():
    # Passwords

    nounList = []

    with open('nouns.txt', 'r') as nounFile:
        for line in nounFile:
            noun = line.strip()
            noun = noun.replace('\'', '')
            nounList.append(noun)

    for i in range(len(listOfEmployers)):
        listOfEmployers[i].password = nounList[r.randrange(len(nounList))] + str(r.randrange(10)) + str(
            r.randrange(10)) + str(r.randrange(10))

    for i in range(len(listOfSeekers)):
        listOfSeekers[i].password = nounList[r.randrange(len(nounList))] + str(r.randrange(10)) + str(
            r.randrange(10)) + str(r.randrange(10))

    for i in range(len(listOfAdmins)):
        listOfAdmins[i].password = nounList[r.randrange(len(nounList))] + str(r.randrange(10)) + str(
            r.randrange(10)) + str(r.randrange(10))


    # for each in passwordList:
    #     print(each)

def setEmpNames():
    # Employer Names

    businessList = []

    with open('business_names.txt', 'r') as businessFile:
        for line in businessFile:
            business = line.strip()
            business = business.replace('\'', '')
            businessList.append(business)

    for i in range(len(listOfEmployers)):
        index = r.randrange(len(businessList))
        listOfEmployers[i].empname = businessList[index]
        del businessList[index]

    # for each in employerList:
    #     print(each)

def setAccBalances():

    for i in range (len(listOfEmployers)):
        listOfEmployers[i].balance = round(r.uniform(-100.5, 300.5), 2)
    for i in range(len(listOfSeekers)):
        listOfSeekers[i].balance = round(r.uniform(-100.5, 300.5), 2)

def setAccCategory():

    category = ['prime', 'gold']
    for i in range(len(listOfEmployers)):
        # Employer Category
        listOfEmployers[i].acategory = category[r.randrange(2)]

    category = ['basic', 'prime', 'gold']
    for i in range(len(listOfSeekers)):
        # Applicant Category
        for i in range(len(listOfSeekers)):
            listOfSeekers[i].acategory = category[r.randrange(3)]

def createJobs():

    for i in range(numJobs):
        listOfJobs.append(AJob('', '', '', '', '', '', '', '', ''))

def associateJobWithEmployer():

    takenJobs = []

    # for i in range (len(listOfEmployers)):
    #     for j in range (r.randrange(1, 5)):
    #         jobIndex = r.randrange(len(listOfJobs))
    #         while listOfJobs[jobIndex] in takenJobs :
    #             jobIndex = r.randrange(len(listOfJobs))
    #         takenJobs.append(listOfJobs[jobIndex])
    #         listOfJobs[i].empUserName = listOfEmployers[i].username
    for i in range (len(listOfJobs)):
        listOfJobs[i].empUserName = listOfEmployers[r.randrange(len(listOfEmployers))].username

def createJobIDs():

    jobIDList = []

    for i in range(len(listOfJobs)):

        tempJobID = r.randrange(5000) + 1000
        while tempJobID in jobIDList:
            tempJobID = r.randrange(5000) + 1000
        jobIDList.append(tempJobID)
        listOfJobs[i].jobID = tempJobID


def createJobTitle():

    titleData = []

    with open('titles_combined.txt') as titleFile:
        for line in titleFile:
            titleName = line.strip()
            titleName = titleName.replace('\'', '')
            titleData.append(titleName)

    for i in range(len(listOfJobs)):
        listOfJobs[i].title = titleData[r.randrange(len(titleData))]

def createJobDate():

    for i in range(len(listOfJobs)):
        date = ""
        date = "2020-" + str(r.randrange(12) + 1) + "-" + str(r.randrange(29) + 1)
        listOfJobs[i].date = date

def createJobDescription():
    # Job Description

    adjRegex = re.compile('\\D+y')

    jobDescriptionList = []
    adjList = []

    yearsExperienceList = ['1', '2', '3', '4', '5']
    maxAdjSize = len("dependable")

    with open('positive_adjectives.txt', 'r') as adjFile:
        for line in adjFile:
            adj = line.strip()
            if adjRegex.match(adj) or len(adj) > maxAdjSize:
                pass
            else:
                adjList.append(adj)

    # for each in adjList:
    #     print(each)

    px = 0

    while px < numJobs:
        jobDesc = "Looking for " + adjList[r.randrange(len(adjList))] + " person. " + yearsExperienceList[
            r.randrange(5)] + "" \
                              " years experience."
        if len(jobDesc) <= 50:
            listOfJobs[px].description = jobDesc
            px = px + 1

def createJobCategory():
    # Job Category

    jobCategoryList = []
    jobCatFullList = []

    with open('job_categories.txt', 'r') as jobCatFile:
        for line in jobCatFile:
            jobCategoryList.append(line.strip())

    for i in range(len(listOfJobs)):
        listOfJobs[i].category = jobCategoryList[r.randrange(len(jobCategoryList))]

def createJobStatus():
    # Job Status List

    # 1 (True) Open, 0 (False) Closed
    for i in range (len(listOfJobs)):
        listOfJobs[i].status = r.randrange(2)

def createEmpNeeded():
    # Number of Positions to fill

    for i in range (len(listOfJobs)):
        listOfJobs[i].empNeeded = r.randrange(5) + 1

def createCreditCards():
    # Credit Card Numbers

    for i in range(numCreditCards):
        ccNumber = ""
        for h in range(16):
            ccNumber += str(r.randrange(10))
        while ccNumber in listOfCreditCards:
            ccNumber = ""
            for h in range(16):
                ccNumber += str(r.randrange(10))
        listOfCreditCards.append(CreditCard('', '', '', '', ''))
        listOfCreditCards[i].CCNumber = ccNumber

def createCreditCreditExpDate():

    for i in range(len(listOfCreditCards)):
        listOfCreditCards[i].expDate = "202" + str(r.randrange(2) + 1) + "-" + str(r.randrange(11) + 1) + "-" + str(r.randrange(28) + 1)

def createCreditCCBNumber():

    for i in range(len(listOfCreditCards)):
        listOfCreditCards[i].CCB = str(r.randrange(10)) + str(r.randrange(10)) + str(r.randrange(10))

def createPADs():

    for i in range(numPAD):
        listOfPADs.append(PAD('', '', '', ''))

def createPADAccNum():

    accountNumberList = []

    for i in range(len(listOfPADs)):
        accNumber = ""
        for j in range(7):
            accNumber += str(r.randrange(10))
        while accNumber in accountNumberList:
            accNumber = ""
            for k in range(7):
                accNumber += str(r.randrange(10))
        accountNumberList.append(accNumber)
        listOfPADs[i].accNum = accNumber

def createPADInstNum():

    bankData = []
    with open('bankCodes.txt', 'r') as bankFile:
        for line in bankFile:
            numeric = filter(str.isdigit, line.strip())
            bankData.append("".join(numeric))

    for i in range(len(listOfPADs)):
        listOfPADs[i].instNum = bankData[r.randrange(len(bankData))]

def createPADBranchNum():

    for i in range(len(listOfPADs)):
        listOfPADs[i].branch = str(r.randrange(10000, 99999))

def createCreditAndPADOwnership():

    tempListPADs = listOfPADs.copy()
    tempListOfCC = listOfCreditCards.copy()
    listOfPADs.clear()
    listOfCreditCards.clear()
    for i in range(len(listOfEmployers)):
        userListCredit = []
        userListPAD = []
        numCC = r.randrange(1,5)
        numOfPAD = r.randrange(1, 5)
        for j in range(numCC):
            CCIndex = r.randrange(len(tempListOfCC))
            userListCredit.append(tempListOfCC[CCIndex].CCNumber)
            listOfCreditCards.append(tempListOfCC.pop(CCIndex))
        for j in range(numOfPAD):
            PADIndex = r.randrange(len(tempListPADs))
            userListPAD.append(tempListPADs[PADIndex].accNum)
            listOfPADs.append(tempListPADs.pop(PADIndex))

        userCreditCards[listOfEmployers[i].username] = userListCredit
        userPADs[listOfEmployers[i].username] = userListPAD

    for i in range(len(listOfSeekers)):

        userListCredit = []
        userListPAD = []
        numCC = r.randrange(1, 5)
        numOfPAD = r.randrange(1, 5)

        for j in range(numCC):
            CCIndex = r.randrange(len(tempListOfCC))
            userListCredit.append(tempListOfCC[CCIndex].CCNumber)
            listOfCreditCards.append(tempListOfCC.pop(CCIndex))
        for j in range(numOfPAD):
            PADIndex = r.randrange(len(tempListPADs))
            userListPAD.append(tempListPADs[PADIndex].accNum)
            listOfPADs.append(tempListPADs.pop(PADIndex))

        userCreditCards[listOfSeekers[i].username] = userListCredit
        userPADs[listOfSeekers[i].username] = userListPAD

def createCreditAndPADDefaultAndAuto():

    for anemployer in listOfEmployers:
        if r.randrange(2) == 0:
            CCToDefault = userCreditCards[anemployer.username][0]
            for CC in listOfCreditCards:
                if CC.CCNumber == CCToDefault:
                    CC.isDefault = 1
                    CC.auto_manual = 1
                    break
        else:
            PADToDefault = userPADs[anemployer.username][0]
            for pad in listOfPADs:
                if pad.accNum == PADToDefault:
                    pad.isDefault = 1
                    pad.auto_manual = 1
                    break

    for aseeker in listOfSeekers:
        if r.randrange(2) == 0:
            CCToDefault = userCreditCards[aseeker.username][0]
            for CC in listOfCreditCards:
                if CC.CCNumber == CCToDefault:
                    CC.isDefault = 1
                    CC.auto_manual = 1
                    break
        else:
            PADToDefault = userPADs[aseeker.username][0]
            for pad in listOfPADs:
                if pad.accNum == PADToDefault:
                    pad.isDefault = 1
                    pad.auto_manual = 1
                    break


def createApplications():

    applicationStatus = ['denied', 'review', 'sent', 'accepted', 'hired']

    for i in range(len(listOfJobs)):
        applicants = []
        applications = []
        numHired = 0
        for i in range(r.randrange(1,30)):
            application = Application('', '', '')
            numHired = createApplicationStatus(application, applicationStatus, i, numHired)
            createApplicationDate(application)
            getApplicationApplicantUserName(applicants, application)
            applications.append(application)
            listOfJobs[i].listOfApp = applications

def getApplicationApplicantUserName(applicants, application):

    applicantUserName = listOfSeekers[r.randrange(len(listOfSeekers))].username
    while applicantUserName in applicants:
        applicantUserName = listOfSeekers[r.randrange(len(listOfSeekers))].username
    application.appUserName = applicantUserName
    applicants.append(applicantUserName)


def createApplicationDate(application):

    application.appdate = "2020-" + str(r.randrange(12) + 1) + "-" + str(r.randrange(29) + 1)


def createApplicationStatus(application, applicationStatus, i, numHired):

    application.appstatus = applicationStatus[r.randrange(len(applicationStatus))]
    if application.appstatus == 'hired':
        numHired = numHired + 1
    if application.appstatus == 'hired' and numHired > listOfJobs[i].empNeeded:
        application.appstatus = applicationStatus[r.randrange(len(applicationStatus) - 1)]
        numHired = numHired - 1
    return numHired

def SQLInsertData():
    # SQL Insert Data
    with open('insert_data.sql', 'w') as sqlFile:
        sqlFile.write('USE cxc55311;\n\n')
        sqlFile.write('INSER' + 'T INTO User(UserName, FirstName, LastName, Email, ContactNumber, Password)\n')
        sqlFile.write('VALUES ')

        for employer in listOfEmployers:
            sqlFile.write(
                '(\'' + employer.username + '\', \'' + employer.firstname + '\', \'' + employer.lastname + '\', \'' + employer.email + '\', \'' + employer.phnumber + '\', \'' + employer.password + '\'),\n')
        for seeker in listOfSeekers:
            sqlFile.write(
                '(\'' + seeker.username + '\', \'' + seeker.firstname + '\', \'' + seeker.lastname + '\', \'' + seeker.email + '\', \'' + seeker.phnumber + '\', \'' + seeker.password + '\'),\n')
        for admin in listOfAdmins:
            sqlFile.write(
                '(\'' + admin.username + '\', \'' + admin.firstname + '\', \'' + admin.lastname + '\', \'' + admin.email + '\', \'' + admin.phnumber + '\', \'' + admin.password + '\'),\n')

        # for (a, b, c, d, e, f) in zip(userNameList, firstNameList, lastNameList, emailList, phoneNumberList, passwordList):
        #     if count == len(userNameList) - 1:
        #         sqlFile.write('(\''+a+'\', \''+b+'\', \''+c+'\', \''+d+'\', \''+e+'\', \''+f+'\');\n')
        #     else:
        #         sqlFile.write('(\''+a+'\', \''+b+'\', \''+c+'\', \''+d+'\', \''+e+'\', \''+f+'\'),\n')
        #         count = count + 1

        sqlFile.write('\nINSER' + 'T INTO Employer(UserName, EmployerName, Category, Balance)\n')
        sqlFile.write('VALUES ')
        for employer in listOfEmployers:
            sqlFile.write(
                '(\'' + employer.username + '\', \'' + employer.empname + '\', \'' + employer.acategory + '\', \'' + str(
                    employer.balance) + '\'),\n')
        # count = 0
        # for (p, q, u, s, t) in zip(employerUserNames, employerList, employerBoolStatus, employerCategory, employerBalance):
        #     if count == len(employerUserNames) - 1:
        #         sqlFile.write('(\''+p+'\', \''+q+'\', \''+str(u)+'\', \''+s+'\', \''+str(t)+'\');\n')
        #     else:
        #         sqlFile.write('(\''+p+'\', \''+q+'\', \''+str(u)+'\', \''+s+'\', \''+str(t)+'\'),\n')
        #         count = count + 1

        sqlFile.write('\nINSER' + 'T INTO Applicant(UserName, Category, Balance)\n')
        sqlFile.write('VALUES ')
        for seeker in listOfSeekers:
            sqlFile.write(
                '(\'' + seeker.username + '\', \'' + seeker.acategory + '\', \'' + str(seeker.balance) + '\'),\n')
        # count = 0
        # for (a, b, c, d) in zip(applicantUserNames, applicantAccountBool, applicantCategory, applicantBalance):
        #     if count == len(applicantUserNames) - 1:
        #         sqlFile.write('(\''+a+'\', \''+str(b)+'\', \''+c+'\', \''+str(d)+'\');\n')
        #     else:
        #         sqlFile.write('(\''+a+'\', \''+str(b)+'\', \''+c+'\', \''+str(d)+'\'),\n')
        #         count = count + 1

        sqlFile.write('\nINSER' + 'T INTO Admin(UserName)\n')
        sqlFile.write('VALUES ')
        for admin in listOfAdmins:
            sqlFile.write('(\'' + admin.username + '\'),\n')
        # count = 0
        # for each in adminUserNames:
        #     if count == len(adminUserNames) - 1:
        #         sqlFile.write('(\''+each+'\');\n')
        #     else:
        #         sqlFile.write('(\''+each+'\'),\n')
        #         count = count + 1

        sqlFile.write('\nINSER' + 'T INTO Job(JobID, EmployerUserName, Title, DatePosted, Description, Category, '
                                  'JobStatus, EmpNeeded)\n')
        sqlFile.write('VALUES ')
        for job in listOfJobs:
            sqlFile.write('(\'' + str(job.jobID) + '\', \'' + job.empUserName + '\', '
                                                                                '\'' + job.title + '\', \'' + str(
                job.date) + '\', \'' + job.description + '\', '
                                                         ' \'' + job.category + '\', \'' + str(
                job.status) + '\', \'' + str(job.empNeeded) + '\'),\n')
        # count = 0
        # for y in range(len(jobIDList)):
        #     if y == len(jobIDList) - 1:
        #         sqlFile.write('(\''+str(jobIDList[y])+'\', \''+employerUserNames[r.randrange(len(employerUserNames))]+'\', '
        #                       '\''+jobTitleList[y]+'\', \''+str(jobDateList[y])+'\', \''+jobDescriptionList[y]+'\', '
        #                        ' \''+jobCatFullList[y]+'\', \''+str(jobStatusList[y])+'\', \''+str(empNeededList[y])+'\');\n')
        #     else:
        #         sqlFile.write('(\''+str(jobIDList[y])+'\', \''+employerUserNames[r.randrange(len(employerUserNames))]+'\', '
        #                       '\''+jobTitleList[y]+'\', \''+str(jobDateList[y])+'\', \''+jobDescriptionList[y]+'\', \''
        #                       +jobCatFullList[y]+'\', \''+str(jobStatusList[y])+'\', \''+str(empNeededList[y])+'\'),\n')

        sqlFile.write('\nINSER' + 'T INTO CreditCardInfo(CCNumber, ExpireDate, CCBNumber, IsDefault, Auto_Manual)\n')
        sqlFile.write('VALUES ')
        for CC in listOfCreditCards:
            sqlFile.write('(\'' + CC.CCNumber + '\', \'' + str(CC.expDate) + '\', \'' + CC.CCB + '\', \'' + str(
                CC.isDefault) + '\', \'' + str(CC.auto_manual) + '\'),\n')
        # count = 0
        # for (a, b, c, d, e) in zip(ccList, ccExpireList, CCBNumberList, ccDefList, ccAutoManualList):
        #     if count == len(ccList) - 1:
        #         sqlFile.write('(\''+a+'\', \''+str(b)+'\', \''+c+'\', \''+str(d)+'\', \''+str(e)+'\');\n')
        #     else:
        #         sqlFile.write('(\''+a+'\', \''+str(b)+'\', \''+c+'\', \''+str(d)+'\', \''+str(e)+'\'),\n')
        #         count = count + 1

        sqlFile.write(
            '\nINSER' + 'T INTO PADInfo(AccountNumber, InstituteNumber, BranchNumber, IsDefault, Auto_Manual)\n')
        sqlFile.write('VALUES ')
        for pad in listOfPADs:
            sqlFile.write('(\'' + pad.accNum + '\', \'' + pad.instNum + '\', \'' + pad.branch + '\', \'' + str(
                pad.isDefault) + '\', \'' + str(pad.auto_manual) + '\'),\n')

        # count = 0
        # for (a, b, c, d, e) in zip(accountNumberList, bankList, branchNumberList, PADDefault, PADAutoManual):
        #     if count == len(accountNumberList) - 1:
        #         sqlFile.write('(\''+a+'\', \''+b+'\', \''+c+'\', \''+str(d)+'\', \''+str(e)+'\');\n')
        #     else:
        #         sqlFile.write('(\''+a+'\', \''+b+'\', \''+c+'\', \''+str(d)+'\', \''+str(e)+'\'),\n')
        #         count = count + 1

        sqlFile.write('\nINSER' + 'T INTO Application(ApplicantUserName, JobID, ApplicationStatus, ApplicationDate)\n')
        sqlFile.write('VALUES ')
        count = 0

        for job in listOfJobs:
            for app in job.listOfApp:
                sqlFile.write(
                    '(\'' + app.appUserName + '\', \'' + str(job.jobID) + '\', \'' + str(app.appstatus) + '\', '
                                                                                                          '\'' + str(
                        app.appdate) + '\'),\n')

        # for a in applicantDict:
        #     for x in applicantDict[a]:
        #         if count == len(applicantDict) - 1:
        #             if x == applicantDict[a][len(applicantDict[a]) - 1]:
        #                 sqlFile.write('(\''+x+'\', \''+str(a)+'\', \''+appStatusList[r.randrange(len(appStatusList))]+'\', '
        #                               '\''+str(applicationDateList[r.randrange(len(applicationDateList))])+'\');\n')
        #             else:
        #                 sqlFile.write('(\''+x+'\', \''+str(a)+'\', \''+appStatusList[r.randrange(len(appStatusList))]+'\', '
        #                               '\''+str(applicationDateList[r.randrange(len(applicationDateList))])+'\'),\n')
        #         else:
        #             sqlFile.write('(\''+x+'\', \''+str(a)+'\', \''+appStatusList[r.randrange(len(appStatusList))]+'\', '
        #                           '\''+str(applicationDateList[r.randrange(len(applicationDateList))])+'\'),\n')
        #     count = count + 1

        sqlFile.write('\nINSER' + 'T INTO EmployerCC(EmployerUserName, CCNumber)\n')
        sqlFile.write('VALUES ')
        for employer in listOfEmployers:
            for cc in userCreditCards[employer.username]:
                sqlFile.write('(\'' + employer.username + '\', \'' + cc + '\'),\n')
        # count = 0
        # for(a, b) in zip(employerUserNames, ccList):
        #     if count == len(employerUserNames) - 1:
        #         sqlFile.write('(\''+a+'\', \''+b+'\');\n')
        #     else:
        #         sqlFile.write('(\''+a+'\', \''+b+'\'),\n')
        #         count = count + 1

        sqlFile.write('\nINSER' + 'T INTO EmployerPAD(EmployerUserName, AccountNumber)\n')
        sqlFile.write('VALUES ')
        for employer in listOfEmployers:
            for pad in userPADs[employer.username]:
                sqlFile.write('(\'' + employer.username + '\', \'' + pad + '\'),\n')
        # count = 0
        # for(a, b) in zip(employerUserNames, accountNumberList):
        #     if count == len(employerUserNames) - 1:
        #         sqlFile.write('(\''+a+'\', \''+b+'\');\n')
        #     else:
        #         sqlFile.write('(\''+a+'\', \''+b+'\'),\n')
        #         count = count + 1

        sqlFile.write('\nINSER' + 'T INTO ApplicantCC(ApplicantUserName, CCNumber)\n')
        sqlFile.write('VALUES ')
        for applicant in listOfSeekers:
            for cc in userCreditCards[applicant.username]:
                sqlFile.write('(\'' + applicant.username + '\', \'' + cc + '\'),\n')
        # count = 0
        # start = 60
        # for a in applicantUserNames:
        #     if count == len(applicantUserNames) - 1:
        #         sqlFile.write('(\''+a+'\', \''+ccList[start]+'\');\n')
        #     else:
        #         sqlFile.write('(\''+a+'\', \''+ccList[start]+'\'),\n')
        #         count = count + 1
        #     start = start + 1

        sqlFile.write('\nINSER' + 'T INTO ApplicantPAD(ApplicantUserName, AccountNumber)\n')
        sqlFile.write('VALUES ')
        for applicant in listOfSeekers:
            for pad in userPADs[applicant.username]:
                sqlFile.write('(\'' + applicant.username + '\', \'' + pad + '\'),\n')

        sqlFile.write("\n\n")  # for regex to remove comma
        # count = 0
        # start = 60
        # stop = 140
        # for (a, b) in zip(applicantUserNames, accountNumberList[start:stop]):
        #     if count == len(applicantUserNames) - 1:
        #         sqlFile.write('(\''+a+'\', \''+b+'\');\n')
        #     else:
        #         sqlFile.write('(\''+a+'\', \''+b+'\'),\n')
        #         count = count + 1
        sqlFile.close()

def SQLRemoveCommas():

    sqlFile = open('insert_data.sql', 'rt')
    withCommas = ''
    for line in sqlFile:
        withCommas += line
    sqlFile.close()
    withSemiColons = re.sub("(,)(\n){2,}", ";\n\n", withCommas)
    fin = open('insert_data.sql', 'wt')
    fin.write(withSemiColons)
    fin.close()


createusers()
makePhoneNumbers()
setFirstAndLastNames()
setEmails()
associatePhNumbersWithUsers()
setUserNames()
setPasswords()
setEmpNames()
setAccBalances()
setAccCategory()
createJobs()
associateJobWithEmployer()
createJobIDs()
createJobTitle()
createJobDate()
createJobDescription()
createJobCategory()
createJobStatus()
createEmpNeeded()
createCreditCards()
createCreditCreditExpDate()
createCreditCCBNumber()
createPADs()
createPADAccNum()
createPADInstNum()
createPADBranchNum()
createCreditAndPADOwnership()
createCreditAndPADDefaultAndAuto()
createApplications()
SQLInsertData()
SQLRemoveCommas()
