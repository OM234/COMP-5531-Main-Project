import random
import re

# Phone Numbers
r = random
numRegex1 = re.compile('^\\d\\d\\d$')
numRegex2 = re.compile('^\\d\\d\\d-\\d\\d\\d$')

phoneNumberList = [''] * 100
areaCodeList = ['514', '438', '450']
phoneNumber = ''

for x in range(100):
    phoneNumber += areaCodeList[r.randrange(3)]
    for y in range(7):
        if numRegex1.match(phoneNumber) or numRegex2.match(phoneNumber):
            phoneNumber += "-" + str(r.randrange(10))
        else:
            phoneNumber += str(r.randrange(10))
    phoneNumberList[x] = phoneNumber
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

# Credit Card Numbers

ccList = []

ccNumber = ""

for k in range(150):
    for h in range(16):
        ccNumber += str(r.randrange(10))

    ccList.append(ccNumber)
    ccNumber = ""

#for i in range(len(ccList)):
#    print(ccList[i])

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
        nounList.append(line.strip())

for u in range(150):
    passwordList.append(nounList[r.randrange(len(nounList))] + str(r.randrange(10)) + str(r.randrange(10)) + str(r.randrange(10)))

# for each in passwordList:
#     print(each)

# Employer Names

businessList = []
employerList = []

with open('business_names.txt', 'r') as businessFile:
    for line in businessFile:
        businessList.append(line.strip())

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
        jobIDList.append(r.randrange(2000)+1000)
        iJ = iJ + 1

# for each in jobIDList:
#     print(each)

# Job Title List

jobTitleList = []
titleData = []

with open('titles_combined.txt') as titleFile:
    for line in titleFile:
        titleData.append(line.strip())

for i in range(150):
    jobTitleList.append(titleData[r.randrange(len(titleData))])

# for each in jobTitleList:
#     print(each)
