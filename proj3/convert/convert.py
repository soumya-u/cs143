import json

with open("nobel-laureates.json", "r") as json_file:
    data = json.load(json_file)

all_data = data['laureates']

fuckppl = set()
fuckorg = set()
fuckwin = set()
fuckprize = set()
fuckaff = set()

p = open("person.del", "w")
o = open("organization.del", "w")
w = open("winners.del", "w")
a = open("aff.del", "w")
pp = open("prizes.del", "w")

for laureate in all_data:
    id = "\\N"
    given_name = "\\N"
    family_name = "\\N"
    gender = "\\N"
    date = "\\N"
    city = "\\N"
    country = "\\N"

    #people
    if "id" in laureate:
        id = laureate['id']
    if "givenName" in laureate:
        if "en" in laureate['givenName']:
            given_name = laureate['givenName']['en']
            given_name = "\"{}\"".format(given_name)
        if "familyName" in laureate:
            if "en" in laureate['familyName']:
                family_name = laureate['familyName']['en']
                family_name = "\"{}\"".format(family_name)
        if "gender" in laureate:
            gender = laureate['gender'
            gender = "\"{}\"".format(gender)

        if "birth" in laureate:
            if "date" in laureate['birth']:
                date = laureate['birth']['date']
                date = "\"{}\"".format(date)

            if "place" in laureate['birth']:
                if "city" in laureate['birth']['place']:
                    if "en" in laureate['birth']['place']['city']:
                        city = laureate['birth']['place']['city']['en']
                        city = "\"{}\"".format(city)
                if "country" in laureate['birth']['place']:
                    if "en" in laureate['birth']['place']['country']:
                        country = laureate['birth']['place']['country']['en']
                        country = "\"{}\"".format(country)
        elif "birthCountry" in laureate:
            country = laureate['birthCountry']['en']
            country = "\"{}\"".format(country)

        entry = "{},{},{},{},{},{},{}\n".format(id, given_name, family_name, gender, date, city, country)
        fuckppl.add(entry)

    orgName = "\\N"
    founded = "\\N"
    org_city = "\\N"
    org_country = "\\N"

    #organizations
    if "orgName" in laureate:
        if "en" in laureate['orgName']:
            orgName = laureate['orgName']['en']
            orgName = "\"{}\"".format(orgName)

        if "founded" in laureate:
            if "date" in laureate['founded']:
                date = laureate['founded']['date']
                date = "\"{}\"".format(date)

            if "place" in laureate['founded']:
                if "city" in laureate['founded']['place']:
                    if "en" in laureate['founded']['place']['city']:
                        org_city = laureate['founded']['place']['city']['en']
                        org_city = "\"{}\"".format(org_city)
                if "country" in laureate['founded']['place']:
                    if "en" in laureate['founded']['place']['country']:
                        org_country = laureate['founded']['place']['country']['en']
                        org_country = "\"{}\"".format(org_country)

        entry = "{},{},{},{},{}\n".format(id, orgName, date, org_city, org_country)
        fuckorg.add(entry)
    
    awardYear = "\\N"
    category = "\\N"
    sortOrder = "\\N"
    portion = "\\N"
    prizeStatus = "\\N"
    dateAwarded = "\\N"
    motivation = "\\N"
    prizeAmount = "\\N"
    aff_name = "\\N"
    aff_city = "\\N"
    aff_country = "\\N"


    #winners
    if "nobelPrizes" in laureate:
        for nobel in laureate['nobelPrizes']:
            if "awardYear" in nobel:
                awardYear = nobel['awardYear']

            if "category" in nobel:
                if "en" in nobel['category']:
                    category = nobel['category']['en']
                    category = "\"{}\"".format(category)

            if "sortOrder" in nobel:
                sortOrder = nobel['sortOrder']

            if "portion" in nobel:
                portion = nobel['portion']
                portion = "\"{}\"".format(portion)

            if "prizeStatus" in nobel:
                prizeStatus = nobel['prizeStatus']
                prizeStatus = "\"{}\"".format(prizeStatus)

            if "dateAwarded" in nobel:
                dateAwarded = nobel['dateAwarded']
                dateAwarded = "\"{}\"".format(dateAwarded)
            
            if "motivation" in nobel:
                if "en" in nobel['motivation']:
                    motivation = nobel['motivation']['en']
                    motivation = "\"{}\"".format(motivation)

            if "prizeAmount" in nobel:
                prizeAmount = nobel['prizeAmount']

            if "affiliations" in nobel:
                for aff in nobel['affiliations']:
                    aff_name = "\\N"
                    aff_city = "\\N"
                    aff_country = "\\N"
                    if "name" in aff:
                        if "en" in aff['name']:
                            aff_name = aff['name']['en']
                            aff_name = "\"{}\"".format(aff_name)
                    if "city" in aff:
                        if "en" in aff['name']:
                            aff_city = aff['city']['en']
                            aff_city = "\"{}\"".format(aff_city)
                    if "country" in aff:
                        if "en" in aff['name']:
                            aff_country = aff['country']['en']
                            aff_country = "\"{}\"".format(aff_country)

                    entry = "{},{},{},{},{},{}\n".format(id, awardYear, category, aff_name, aff_city, aff_country)
                    fuckaff.add(entry)

            entry = "{},{},{},{},{},{},{}\n".format(id, awardYear, category, sortOrder, portion, prizeStatus, motivation)
            fuckwin.add(entry)
    
            #prizes
            entry = "{},{},{},{}\n".format(awardYear, category, dateAwarded, prizeAmount)
            fuckprize.add(entry)


for i in fuckppl:
    p.write(i)

for i in fuckorg:
    o.write(i)

for i in fuckwin:
    w.write(i)

for i in fuckaff:
    a.write(i)

for i in fuckprize:
    pp.write(i)


p.close()
o.close()
w.close()
a.close()
pp.close()
