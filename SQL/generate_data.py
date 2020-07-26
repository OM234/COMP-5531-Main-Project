import random
import re

import random
import re

# Phone Numbers
r = random
numRegex1 = re.compile('^\\d\\d\\d$')
numRegex2 = re.compile('^\\d\\d\\d-\\d\\d\\d$')

phoneNumberList = []
areaCodeList = ['514', '438', '450']
phoneNumber = ''

for x in range(150):
    phoneNumber += areaCodeList[r.randrange(3)]
    for y in range(7):
        if numRegex1.match(phoneNumber) or numRegex2.match(phoneNumber):
            phoneNumber += "-" + str(r.randrange(10))
        else:
            phoneNumber += str(r.randrange(10))
    phoneNumberList.append(phoneNumber)
#    print(phoneNumberList[x])
    phoneNumber = ''

# First Names, Last Names

firstNameList = []
lastNameList = []

with open('names.txt', 'r') as nameFile:
    for line in nameFile:
        firstNameList.append(line.strip())

# for i in range(len(firstNameList)):
#     print(firstNameList[i])

with open('lastnames.txt', 'r') as lastNameFile:
    for line in lastNameFile:
        lastNameList.append(line.strip())

for i in range(len(lastNameList)):
    lastNameList[i] = lastNameList[i].lower()
    lastNameList[i] = lastNameList[i].capitalize()
#    print(lastNameList[i])

# Emails

emailList = []
emailString = ""
emailDomain = ['coldmail.com', 'hmail.ca', 'zahoo.com']

for i in range(150):
    emailString += firstNameList[i] + lastNameList[i] + str(r.randrange(10)) + str(r.randrange(10)) + "@" +\
                   emailDomain[r.randrange(2)]
    emailList.append(emailString)
    emailString = ""

#for each in emailList:
#    print(each)

# Usernames

userNameList = []
userString = ""
userCount = 0

while userCount < 150:
    userString += firstNameList[userCount] + "_" + lastNameList[userCount][0] + str(r.randrange(10)) + str(r.randrange(10))
    if userString not in userNameList:
        userNameList.append(userString)
        userCount = userCount + 1
    userString = ""

# for each in userNameList:
#     print(each)

# Passwords

nounList = []
passwordList = []

with open('nouns.txt', 'r') as nounFile:
    for line in nounFile:
        noun = line.strip()
        noun = noun.replace('\'', '')
        nounList.append(noun)

for u in range(150):
    passwordList.append(nounList[r.randrange(len(nounList))] + str(r.randrange(10)) + str(r.randrange(10)) + str(r.randrange(10)))

# for each in passwordList:
#     print(each)

# Employer Names

businessList = []
employerList = []

with open('business_names.txt', 'r') as businessFile:
    for line in businessFile:
        business = line.strip()
        business = business.replace('\'', '')
        businessList.append(business)

for p in range(150):
    employerList.append(businessList[r.randrange(len(businessList))])

# for each in employerList:
#     print(each)

# Employer Balance

employerBalance = []

for i in range(150):
    employerBalance.append(round(r.uniform(-100.5, 300.5), 2))

#for each in employerBalance:
#    print(each)

# Employer Account Status

employerBoolStatus = [None] * len(employerBalance)

for i in range(len(employerBoolStatus)):
    if(employerBalance[i] < 0):
        employerBoolStatus[i] = 0
    else:
        employerBoolStatus[i] = 1

# for each in employerBool:
#     print(each)

# Employer Category

category = ['basic', 'prime', 'gold']
employerCategory = []

for i in range(150):
    employerCategory.append(category[r.randrange(2)+1])

# for each in employerCategory:
#     print(each)

# Applicant balance

applicantBalance = []

for i in range(150):
    applicantBalance.append(round(r.uniform(-100.5, 300.5), 2))

# Applicant Account Status

applicantAccountBool = [None] * len(applicantBalance)

for j in range(len(applicantAccountBool)):
    if applicantBalance[j] < 0:
        applicantAccountBool[j] = 0
    else:
        applicantAccountBool[j] = 1

# Applicant Category

applicantCategory = []

for p in range(150):
    applicantCategory.append(category[r.randrange(3)])

# for each in applicantCategory:
#     print(each)

# Employer, Applicant, and Admin Usernames

applicantUserNames = []
employerUserNames = []
adminUserNames = []

for i in range(80):
    applicantUserNames.append(userNameList[i])

# for each in applicantUserNames:
#     print(each)

start = 80
stop = 140

for j, uName in enumerate(userNameList[start:stop], start=start):
    employerUserNames.append(uName)

# for each in employerUserNames:
#     print(each)

start = 140
stop = 150

for k, uName in enumerate(userNameList[start:stop], start=start):
    adminUserNames.append(uName)

# for each in adminUserNames:
#     print(each)

# Job ID

jobIDList = []

iJ = 0;

while iJ < 150:
    tempJobID = r.randrange(2000)+1000
    if tempJobID not in jobIDList:
        jobIDList.append(tempJobID)
        iJ = iJ + 1

# for each in jobIDList:
#     print(each)

# Job ID & ApplicantUserName List

applicantDict = {}
bagJobID = []
bagAppUserNames = []

iH = 0
while iH < len(jobIDList):
    bagJobID.append(jobIDList[r.randrange(len(jobIDList))])
    iH = iH + 1

for each in bagJobID:
    applicantDict[each] = [applicantUserNames[r.randrange(len(applicantUserNames))]]

iH = 0

while iH < len(bagJobID):
    randAppUser = applicantUserNames[r.randrange(len(applicantUserNames))]
    randJobID = bagJobID[r.randrange(len(bagJobID))]
    if randAppUser not in applicantDict[randJobID]:
        applicantDict[randJobID].append(randAppUser)
        iH = iH + 1

# print(applicantDict)

dignifiedJobIDList = []
dignifiedUserAppList = []

for each in applicantDict:
    dignifiedJobIDList.append(each)
    for names in applicantDict[each]:
        dignifiedUserAppList.append(names)

# print(dignifiedJobIDList)
# print(dignifiedUserAppList)

# Job Title List

jobTitleList = []
titleData = []

with open('titles_combined.txt') as titleFile:
    for line in titleFile:
        titleName = line.strip()
        titleName = titleName.replace('\'', '')
        titleData.append(titleName)

for i in range(150):
    jobTitleList.append(titleData[r.randrange(len(titleData))])

# for each in jobTitleList:
#     print(each)

# Job Date List

jobDateList = []

for h in range(150):
    date = "2020-"+str(r.randrange(12)+1)+"-"+str(r.randrange(29)+1)
    jobDateList.append(date)
    date = ""

# for each in jobDateList:
#     print(each)


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

while px < 150:
    jobDesc = "Looking for "+adjList[r.randrange(len(adjList))]+" person. "+yearsExperienceList[r.randrange(5)]+"" \
     " years experience."
    if len(jobDesc) <= 50:
        jobDescriptionList.append(jobDesc)
        px = px + 1

# for each in jobDescriptionList:
#     print(each)
#     print(len(each))

# Job Category

jobCategoryList = []
jobCatFullList = []

with open('job_categories.txt', 'r') as jobCatFile:
    for line in jobCatFile:
        jobCategoryList.append(line.strip())

for j in range(len(jobIDList)):
    jobCatFullList.append(jobCategoryList[r.randrange(len(jobCategoryList))])

# Job Status List

# 1 (True) Open, 0 (False) Closed
jobStatusList = []

for k in range(150):
    jobStatusList.append(r.randrange(2))

# Number of Positions to fill

empNeededList = []

for w in range(150):
    empNeededList.append(r.randrange(5)+1)

# Credit Card Numbers

ccList = []

ccNumber = ""
k = 0
while k < 150:
    for h in range(16):
        ccNumber += str(r.randrange(10))
    if ccNumber not in ccList:
        ccList.append(ccNumber)
        k = k + 1
    ccNumber = ""

# for i in range(len(ccList)):
#     print(ccList[i])

# Credit Card Expire Date

ccExpireList = []

for q in range(150):
    ccExpireList.append("202"+str(r.randrange(2)+1)+"-"+str(r.randrange(11)+1)+"-"+str(r.randrange(28)+1))

# for each in ccExpireList:
#     print(each)

# Credit Card CCBNUmber List

CCBNumberList = []

for o in range(150):
    CCBNumberList.append(str(r.randrange(10))+str(r.randrange(10))+str(r.randrange(10)))

# for each in CCBNumberList:
#     print(each)

# Credit Card Default List

ccDefList = []

for u in range(150):
    ccDefList.append(r.randrange(2))

# Credit Card Auto_Manual List

ccAutoManualList = []

for y in range(150):
    ccAutoManualList.append(r.randrange(2))

# PAD INFO Account Number List

accountNumberList = []
accNumber = ""

a = 0

while a < 150:
    for b in range(7):
        accNumber += str(r.randrange(10))
    if accNumber not in accountNumberList:
        accountNumberList.append(accNumber)
        a = a + 1
    accNumber = ""

# for each in accountNumberList:
#     print(each)

# Bank Institution Numbers

bankData = []

with open('bankCodes.txt', 'r') as bankFile:
    for line in bankFile:
        numeric = filter(str.isdigit, line.strip())
        bankData.append("".join(numeric))

bankList = []

for g in range(150):
    bankList.append(bankData[r.randrange(len(bankData))])

# for each in bankList:
#     print(each)

# Bank Branch Numbers

branchNumberList = []

for t in range(150):
    branchNumberList.append(str(r.randrange(99)+1))

# for each in branchNumberList:
#     print(each)

# PADInfo Default List

PADDefault = []

for h in range(150):
    if ccDefList[h] == 0:
        PADDefault.append(1)
    else:
        PADDefault.append(0)

# PADInfo Auto Manual List

PADAutoManual = []

for i in range(150):
    PADAutoManual.append(r.randrange(2))

# Application Status List

applicationStatus = ['denied', 'review', 'sent', 'accepted', 'hired']

appStatusList = []

for x in range(150):
    appStatusList.append(applicationStatus[r.randrange(len(applicationStatus))])

# for each in appStatusList:
#     print(each)

# Application Date

applicationDateList = []

for o in range(150):
    applicationDateList.append("2020-"+str(r.randrange(12)+1)+"-"+str(r.randrange(29)+1))

# SQL Insert Data

with open('insert_data.sql', 'w') as sqlFile:
    sqlFile.write('USE project;\n')
    sqlFile.write('INSER'+'T INTO User(UserName, FirstName, LastName, Email, ContactNumber, Password)\n')
    sqlFile.write('VALUES ')
    count = 0
    for (a, b, c, d, e, f) in zip(userNameList, firstNameList, lastNameList, emailList, phoneNumberList, passwordList):
        if count == len(userNameList) - 1:
            sqlFile.write('(\''+a+'\', \''+b+'\', \''+c+'\', \''+d+'\', \''+e+'\', \''+f+'\');\n')
        else:
            sqlFile.write('(\''+a+'\', \''+b+'\', \''+c+'\', \''+d+'\', \''+e+'\', \''+f+'\'),\n')
            count = count + 1

    sqlFile.write('\nINSER'+'T INTO Employer(UserName, EmployerName, AccStatus, Category, Balance)\n')
    sqlFile.write('VALUES ')
    count = 0
    for (p, q, u, s, t) in zip(employerUserNames, employerList, employerBoolStatus, employerCategory, employerBalance):
        if count == len(employerUserNames) - 1:
            sqlFile.write('(\''+p+'\', \''+q+'\', \''+str(u)+'\', \''+s+'\', \''+str(t)+'\');\n')
        else:
            sqlFile.write('(\''+p+'\', \''+q+'\', \''+str(u)+'\', \''+s+'\', \''+str(t)+'\'),\n')
            count = count + 1

    sqlFile.write('\nINSER'+'T INTO Applicant(UserName, AccStatus, Category, Balance)\n')
    sqlFile.write('VALUES ')
    count = 0
    for (a, b, c, d) in zip(applicantUserNames, applicantAccountBool, applicantCategory, applicantBalance):
        if count == len(applicantUserNames) - 1:
            sqlFile.write('(\''+a+'\', \''+str(b)+'\', \''+c+'\', \''+str(d)+'\');\n')
        else:
            sqlFile.write('(\''+a+'\', \''+str(b)+'\', \''+c+'\', \''+str(d)+'\'),\n')
            count = count + 1

    sqlFile.write('\nINSER'+'T INTO Admin(UserName)\n')
    sqlFile.write('VALUES ')
    count = 0
    for each in adminUserNames:
        if count == len(adminUserNames) - 1:
            sqlFile.write('(\''+each+'\');\n')
        else:
            sqlFile.write('(\''+each+'\'),\n')
            count = count + 1

    sqlFile.write('\nINSER'+'T INTO Job(JobID, EmployerUserName, Title, DatePosted, Description, Category, '
                            'JobStatus, EmpNeeded)\n')
    sqlFile.write('VALUES ')
    count = 0
    for y in range(len(jobIDList)):
        if y == len(jobIDList) - 1:
            sqlFile.write('(\''+str(jobIDList[y])+'\', \''+employerUserNames[r.randrange(len(employerUserNames))]+'\', '
                          '\''+jobTitleList[y]+'\', \''+str(jobDateList[y])+'\', \''+jobDescriptionList[y]+'\', '
                           ' \''+jobCatFullList[y]+'\', \''+str(jobStatusList[y])+'\', \''+str(empNeededList[y])+'\');\n')
        else:
            sqlFile.write('(\''+str(jobIDList[y])+'\', \''+employerUserNames[r.randrange(len(employerUserNames))]+'\', '
                          '\''+jobTitleList[y]+'\', \''+str(jobDateList[y])+'\', \''+jobDescriptionList[y]+'\', \''
                          +jobCatFullList[y]+'\', \''+str(jobStatusList[y])+'\', \''+str(empNeededList[y])+'\'),\n')

    sqlFile.write('\nINSER'+'T INTO CreditCardInfo(CCNumber, ExpireDate, CCBNumber, IsDefault, Auto_Manual)\n')
    sqlFile.write('VALUES ')
    count = 0
    for (a, b, c, d, e) in zip(ccList, ccExpireList, CCBNumberList, ccDefList, ccAutoManualList):
        if count == len(ccList) - 1:
            sqlFile.write('(\''+a+'\', \''+str(b)+'\', \''+c+'\', \''+str(d)+'\', \''+str(e)+'\');\n')
        else:
            sqlFile.write('(\''+a+'\', \''+str(b)+'\', \''+c+'\', \''+str(d)+'\', \''+str(e)+'\'),\n')
            count = count + 1

    sqlFile.write('\nINSER'+'T INTO PADInfo(AccountNumber, InstituteNumber, BranchNumber, IsDefault, Auto_Manual)\n')
    sqlFile.write('VALUES ')
    count = 0
    for (a, b, c, d, e) in zip(accountNumberList, bankList, branchNumberList, PADDefault, PADAutoManual):
        if count == len(accountNumberList) - 1:
            sqlFile.write('(\''+a+'\', \''+b+'\', \''+c+'\', \''+str(d)+'\', \''+str(e)+'\');\n')
        else:
            sqlFile.write('(\''+a+'\', \''+b+'\', \''+c+'\', \''+str(d)+'\', \''+str(e)+'\'),\n')
            count = count + 1

    sqlFile.write('\nINSER'+'T INTO Application(ApplicantUserName, JobID, ApplicationStatus, ApplicationDate)\n')
    sqlFile.write('VALUES ')
    count = 0

    for a in applicantDict:
        for (x, b, c, d) in zip(applicantDict[a], jobIDList, appStatusList, applicationDateList):
            if count == len(applicantDict) - 1:
                if x == applicantDict[a][len(applicantDict[a]) - 1]:
                    sqlFile.write('(\''+x+'\', \''+str(a)+'\', \''+c+'\', \''+str(d)+'\');\n')
                else:
                    sqlFile.write('(\''+x+'\', \''+str(a)+'\', \''+c+'\', \''+str(d)+'\'),\n')
            else:
                sqlFile.write('(\''+x+'\', \''+str(a)+'\', \''+c+'\', \''+str(d)+'\'),\n')
        count = count + 1

    sqlFile.write('\nINSER'+'T INTO EmployerCC(EmployerUserName, CCNumber)\n')
    sqlFile.write('VALUES ')
    count = 0
    for(a, b) in zip(employerUserNames, ccList):
        if count == len(employerUserNames) - 1:
            sqlFile.write('(\''+a+'\', \''+b+'\');\n')
        else:
            sqlFile.write('(\''+a+'\', \''+b+'\'),\n')
            count = count + 1

    sqlFile.write('\nINSER'+'T INTO EmployerPAD(EmployerUserName, AccountNumber)\n')
    sqlFile.write('VALUES ')
    count = 0
    for(a, b) in zip(employerUserNames, accountNumberList):
        if count == len(employerUserNames) - 1:
            sqlFile.write('(\''+a+'\', \''+b+'\');\n')
        else:
            sqlFile.write('(\''+a+'\', \''+b+'\'),\n')
            count = count + 1

    sqlFile.write('\nINSER'+'T INTO ApplicantCC(ApplicantUserName, CCNumber)\n')
    sqlFile.write('VALUES ')
    count = 0
    start = 60
    for a in applicantUserNames:
        if count == len(applicantUserNames) - 1:
            sqlFile.write('(\''+a+'\', \''+ccList[start]+'\');\n')
        else:
            sqlFile.write('(\''+a+'\', \''+ccList[start]+'\'),\n')
            count = count + 1
        start = start + 1

    sqlFile.write('\nINSER'+'T INTO ApplicantPAD(ApplicantUserName, AccountNumber)\n')
    sqlFile.write('VALUES ')
    count = 0
    start = 60
    stop = 140
    for (a, b) in zip(applicantUserNames, accountNumberList[start:stop]):
        if count == len(applicantUserNames) - 1:
            sqlFile.write('(\''+a+'\', \''+b+'\');\n')
        else:
            sqlFile.write('(\''+a+'\', \''+b+'\'),\n')
            count = count + 1
