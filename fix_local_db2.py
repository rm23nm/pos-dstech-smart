import mysql.connector

try:
    mydb = mysql.connector.connect(
      host='localhost',
      user='root',
      password='',
      database='xpos'
    )
    mycursor = mydb.cursor()
    try:
        mycursor.execute('ALTER TABLE customer_memberships ADD COLUMN ValidFrom DATETIME NULL AFTER KodePaketMember')
        print('Added ValidFrom')
    except Exception as e:
        print('ValidFrom info:', e)
    
    mydb.commit()
    print('Local DB updated successfully.')
except Exception as e:
    print('Connection error:', e)
