import RPi.GPIO as GPIO
from mfrc522 import SimpleMFRC522

writer = SimpleMFRC522()
text = input('Data: ')

id, text_written = writer.write(text)
print(f"ID: {id}")  
print(f"Text Written: {text_written}")
