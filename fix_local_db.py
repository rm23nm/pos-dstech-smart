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
        mycursor.execute('ALTER TABLE fakturpenjualanheader ADD COLUMN NoAntrian VARCHAR(255) NULL')
        print('Added NoAntrian')
    except Exception as e:
        print('NoAntrian info:', e)
    
    try:
        mycursor.execute("ALTER TABLE fakturpenjualanheader ADD COLUMN peracikan_status VARCHAR(255) NULL DEFAULT 'menunggu'")
        print('Added peracikan_status')
    except Exception as e:
        print('peracikan_status info:', e)
        
    mydb.commit()
    print('Local DB updated successfully.')
except Exception as e:
    print('Connection error:', e)
