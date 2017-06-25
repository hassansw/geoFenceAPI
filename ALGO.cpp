uiCHECK OUTPOST IN THE FECNCE

1. INITIALIZE DATABASE CONNECTION
2. RETRIEVE  OutpostData from DATABASE into Outpost
3. ASSIGN OutpostLocation in DEGREES to OutLocation FROM LATITUDE and LONGITUDE
4. GET and ASSIGN USER_ACCIDENT_LOCATION from RECEIVED_MODULAR_DATA to UserLocation
5. CONVERT UserLocation into DEGREE FORMAT
6. FOR every outpost_Location in OutpostLocation do
      a. ASSIGN outLat = outpost.latitude
      b. ASSIGN outLong = outpost.longitude
      c. GET and ASSIGN OutLocation from outLat and outLong
      d. IN_RANGE = IS_WITHINTHE_FENCE using OutLocation and UserLocation
      e. IF IN_RANGE == TRUE
            i.SEND_REQUEST_FOR_HELP
      f. ENDIF
7. END

SEND INFO TO FAMILY

1. INITIALIZE DATABASE CONNECTION
2. RETRIEVE USER_DATA from DATABASE into User;
3. FOR every User_Family in User do
   a. SEND_CURRENT_USER_STATUS to Family
   b. END
4. END


IS_WITHINTHE_FENCE

1. ASSIGN and GET CENTER_POINT to Center
2. ASSIGN and GET CENTER_OUTPOST
3. inRange = min -> GET_LATITUDE_IN_DEGREES() COMPARE location -> GET_LATITUDE_IN_DEGREES();
4. inRange = inRange AND min -> GET_LATITUDE_IN_DEGREES() COMPARE  location-> GET_LATITUDE_IN_DEGREES();
5. inRange = inRange AND max -> GET_LATITUDE_IN_DEGREES() COMPARE  location-> GET_LATITUDE_IN_DEGREES();
6. inRange = inRange AND min -> GET_LATITUDE_IN_DEGREES() COMPARE  location-> GET_LATITUDE_IN_DEGREES();
7. RETURN inRange STATUS
