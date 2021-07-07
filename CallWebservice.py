# importing the requests library
import requests

def send(dateTimeArg,temperaturArg,tokenArg):
# api-endpoint
 URL = "http://"
  
# location given here
 dateTime = dateTimeArg
 temperatur = temperaturArg
 TOKEN = tokenArg
  
# defining a params dict for the parameters to be sent to the API
 PARAMS = {'DateTime':dateTime, 'Temperatur': temperatur}
  
# sending get request and saving the response as response object
 r = requests.get(url = URL, params = PARAMS, token = TOKEN)
  
 print(r)
