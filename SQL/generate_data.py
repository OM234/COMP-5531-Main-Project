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
#   print(phoneNumberList[x])
    phoneNumber = ''

# First Names, Last Names

firstNameList = []
lastNameList = []

with open('names.txt', 'r') as nameFile:
    for line in nameFile:
        firstNameList.append(line.strip())

#for i in range(len(firstNameList)):
    #print(firstNameList[i])

with open('lastnames.txt', 'r') as lastNameFile:
    for line in lastNameFile:
        lastNameList.append(line.strip())

for i in range(len(lastNameList)):
    lastNameList[i] = lastNameList[i].lower()
    lastNameList[i] = lastNameList[i].capitalize()
#    print(lastNameList[i])

# Credit Card Numbers

ccList = []
# re: String to BIGINT with SQL
ccNumber = ""

for k in range(100):
    for h in range(16):
        ccNumber += str(r.randrange(10))

    ccList.append(ccNumber)
    ccNumber = ""

#for i in range(len(ccList)):
#    print(ccList[i])

# Emails

emailList = []
emailString = ""
emailDomain = ['hotmail.com', 'gmail.ca', 'yahoo.com']

for i in range(100):
    emailString += firstNameList[i] + lastNameList[i] + str(r.randrange(10)) + str(r.randrange(10)) + "@" +\
                   emailDomain[r.randrange(2)]
    emailList.append(emailString)
    emailString = ""

#for each in emailList:
#    print(each)

# Usernames

userNameList = []
userString = ""

for j in range(100):
    userString += firstNameList[j] + "_" + lastNameList[j][0] + str(r.randrange(10)) + str(r.randrange(10))
    userNameList.append(userString)
    userString = ""

#for each in userNameList:
#    print(each)

# Passwords

nounList = []
passwordList = []

with open('nouns.txt', 'r') as nounFile:
    for line in nounFile:
        nounList.append(line.strip())

for u in range(100):
    passwordList.append(nounList[r.randrange(len(nounList))] + str(r.randrange(10)) + str(r.randrange(10)) + str(r.randrange(10)))

#for each in passwordList:
#   print(each)

# Employer Names

businessList = []
employerList = []

with open('business_names.txt', 'r') as businessFile:
    for line in businessFile:
        businessList.append(line.strip())

for p in range(100):
    employerList.append(businessList[r.randrange(len(businessList))])

#for each in employerList:
#    print(each)


