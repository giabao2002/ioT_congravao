import RPi.GPIO as GPIO
import time
from signal import signal, SIGTERM, SIGHUP, pause
from rpi_lcd import LCD
import mysql.connector
import multiprocessing
from mfrc522 import SimpleMFRC522
from datetime import datetime

TIME_CLOSE_DOOR = 2

MOTOR_SERVO_1 = 17
MOTOR_SERVO_2 = 23
BUTTON_OPEN_1 = 27
BUTTON_OPEN_2 = 18
LED_1 = 22

LCD = LCD()
GPIO.setwarnings(False)
GPIO.setmode(GPIO.BCM)

GPIO.setup(MOTOR_SERVO_1, GPIO.OUT)
GPIO.setup(MOTOR_SERVO_2, GPIO.OUT)
GPIO.setup(BUTTON_OPEN_1, GPIO.IN, pull_up_down=GPIO.PUD_UP)
GPIO.setup(BUTTON_OPEN_2, GPIO.IN, pull_up_down=GPIO.PUD_UP)
GPIO.setup(LED_1, GPIO.OUT)

p = [0, 0]
p[0] = GPIO.PWM(MOTOR_SERVO_1, 50) # GPIO 17 for PWM with 50Hz
p[1] = GPIO.PWM(MOTOR_SERVO_2, 50) # GPIO 23 for PWM with 50Hz

READER_RFID = SimpleMFRC522()

DOOR_1 = False
DOOR_2 = False

MAX_TOTAL_DEBT = 100000
MONEY_THE_DAY = 3000
MONEY_OVERNIGHT = 7000


# region Database
conn = mysql.connector.connect(host = 'localhost', user = 'user1', passwd = '123456', database = 'iot_ravaocong')
def query(sql):
    global conn
    cursor = conn.cursor()
    cursor.execute(sql)
    conn.commit()
    cursor.close()
    return cursor.lastrowid

def getQuery(sql):
    global conn
    cursor = conn.cursor()
    cursor.execute(sql)
    columns = [col[0] for col in cursor.description]
    rows = cursor.fetchall()
    results = []
    for row in rows:
        result_dict = dict(zip(columns, row))
        results.append(result_dict)
    cursor.close()
    return results

def getDataInObject(object, key):
    if key in object:
        return object[key]
    return None

def openTheDoor(id = 0):
    p[id].start(0)
    p[id].ChangeDutyCycle(8.5)
    time.sleep(0.1)
    p[id].ChangeDutyCycle(0)

def closeTheDoor(id = 0):
    p[id].start(0)
    p[id].ChangeDutyCycle(3.5)
    time.sleep(0.1)
    p[id].ChangeDutyCycle(0)

# endregion

# region Door
def openAndCloseTheDoor(id = 0):
    turnOnTheLed()
    openTheDoor(id)
    time.sleep(TIME_CLOSE_DOOR)
    closeTheDoor(id)
    turnOffTheLed()
    clearDisplay()
# endregion

# region LED
def turnOnTheLed():
    GPIO.output(LED_1, GPIO.HIGH)

def turnOffTheLed():
    GPIO.output(LED_1, GPIO.LOW)
# endregion

# region LCD
def displayMessage(message, line = 1):
    LCD.text(message, line)

def displayMessageAndClear(message, line = 1):
    LCD.clear()
    LCD.text(message, line)

def clearDisplay():
    LCD.clear()
# endregion

def readerRFID():
    while True:
        id, text = READER_RFID.read()
        user = []
        user = getQuery('SELECT * FROM users WHERE access_token = "' + str(text) + '"')
        if (len(user) == 0):
            displayMessageAndClear('Access denied!')
            time.sleep(2)
            clearDisplay()
            continue
        user_id = getDataInObject(user[0], 'id')
        history = getQuery('SELECT * FROM histories WHERE user_id = "' + str(user_id) + '" ORDER BY id DESC LIMIT 1')
        typeDoor = "in"
        if (len(history) == 0):
            typeDoor = "in"
        else:
            history_type = getDataInObject(history[0], 'type')
            if (history_type == "in"):
                typeDoor = "out"
            else:
                typeDoor = "in"

        money_debt = 0
        if (len(history) > 0):
            distance = abs(distanceDay(getDataInObject(history[0], 'created_at')))
            if distance == 0:
                money_debt = MONEY_THE_DAY
            else:
                money_debt = MONEY_OVERNIGHT * (distance - 1) + MONEY_THE_DAY

        if (typeDoor == "in"):
            displayMessageAndClear('Welcome!')
            displayMessage(getDataInObject(user[0], 'name'), 2)
            openAndCloseTheDoor(0)
        else:
            if (money_debt + getDataInObject(user[0], 'money') > MAX_TOTAL_DEBT):
                displayMessageAndClear('Maximum debt amount!')
                displayMessage(str(money_debt + getDataInObject(user[0], 'money')) + ' VND', 2)
                time.sleep(2)
                clearDisplay()
                continue
            query('UPDATE users SET money = "' + str(money_debt + getDataInObject(user[0], 'money')) + '" WHERE id = "' + str(user_id) + '"')
            displayMessageAndClear('Goodbye!')
            displayMessage(getDataInObject(user[0], 'name'), 2)
            openAndCloseTheDoor(1)

        query('INSERT INTO histories (user_id, type) VALUES ("' + str(user_id) + '", "' + str(typeDoor) + '")')
        
        time.sleep(0.1)


def distanceDay(day):
    day1 = datetime.strptime(str(day), '%Y-%m-%d %H:%M:%S')
    day2 = datetime.now()
    return (day1 - day2).days

clearDisplay()

print('Running ...')

process = multiprocessing.Process(target=readerRFID)
process.start()

try:
    while True:
        button_state_1 = GPIO.input(BUTTON_OPEN_1)
        button_state_2 = GPIO.input(BUTTON_OPEN_2)
        if button_state_1 == False:
            print('Button 1 Pressed...')
            openAndCloseTheDoor(0)
        if button_state_2 == False:
            print('Button 2 Pressed...')
            openAndCloseTheDoor(1)

        time.sleep(0.1)

except KeyboardInterrupt:
    p.stop()
    GPIO.cleanup()
