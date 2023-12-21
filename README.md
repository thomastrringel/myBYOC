# myBYOC Mercedes Developer Bring Your Own Car BYOC Example (ready to use 2022/12/17)
# 31/10/23 - service deprecated by Mercedes-Benz
# 17/12/22 - updated new Mercedes server address

Mercedes offers some cool features to request data from your own car. This is nice for programmers like me. So I tried it and it worked. While implmenting it I faced some troubles (which have nothing to do with Merdeces API, but more with some new proecedures for me). This Repos shall help other guys to implement it.

# purpose of this repository
- this is for educational purposes
- I have developed this repository since I faced quite some troubles in getting the data from my car and understand how to interprete it in the correct manner
- I am somewhat experienced in programming, but oAuth2 was new to me and I faced most problems in working with the data I got from mercedes
- is it an arry? how do I have to access each value? how to make a 64bit key? how is oAuth2 working? etc.
- therefore this repository is to explain step by step how I did it
- it is not for commercial use and I do know that it is not a good idea to keep the client-Secret in the php file itself, feel free to improve :-)

# Prerequesites:
- own domain like www.domain.de with php Server running where you put your php files
- own Mercedes-Benz Car
- MercedesMe Connect Account with activation of all services (! important is to accept that 3rd Parties can request data)
- Mercedes Developer Account https://developer.mercedes-benz.com/ 

# preparing Mercedes Developer Account 
- create a new project in Mercedes Developer Console
- you'll get a Client-ID which you will need for the files here
- you'll get a Client-Secret which you will need for the files here
- you'll need to define a Redirect-URL which has to look like https://www.yourdomain.com/2GetAuthorizationCode.php
- be aware: you have to add the '2GetAuthorizationCode.php' => this is the redirecting filename after the oAuth Procedure
- if you want to install the files in a subdirectory you may add this, e. g. https://www.yourdomain.com/subdirectory/2GetAuthorizationCode.php
- in your project you have to add the products
- i have added "electric vehicle status" (I do have a hybrid)
- i have added "fuel status"
- i have added "pay as you drive insurance"
- i have added "vehicle lock status"
- i have added "vehicle status"
- all of the products deliver different data sets
- that should be all to prepare Mercedes Developer Account

# preparing you internetdomain
- copy all files from the Application folder into the respective folder, e. g. https://www.yourdomain.com/subdirectory/
- accept Cookie Setting in your Internet Explorer or Google Chrom Settings

# how is it working?
1.) Load https://www.yourdomain.com/subdirectory/1IdentifyUser.php?service=1 in your Browser
=> you see a simple page which offers you the different products to request
=> you can change the ?service=1 to any of 2/3/4/5 and you will then get the other products (if you omit ?service=x default is service=1)
=> let us assume you have loaded https://www.yourdomain.com/subdirectory/1IdentifyUser.php?service=4 which is for evstatus
=> click on the Link "STARTE AUTHENTIFIZIERUNG CONNECT ME" this will redirect you to the ConnectMe Login page
=> type in your ConnectMe Credentials
=> eventually you will see (only once) the request that someone with the name of your prject (which you have defined in the Mercedes Developer Portal) wants to access your data - and you have to accept this

2.) if the oAuth Procedure was able to accept your credentials you mal see another very simple page
=> you have been redirected to https://www.yourdomain.com/subdirectory/2GetAuthorizationCode.php
=> click on the link "STARTE DATENABFRAGE BYOCAR"

3.) if everything worked fine you can see another page https://www.yourdomain.com/subdirectory/3GetAllData.php
=> first a curl_exec result
=> second a JSON dataset 
=> with plotly you may find SOC and RangeElectric a little bit downwards on the same page

# trouble shooting and other hints
- the file 3GetAllData.php ist the frame for all data
- to be more flexible the specific data is defines in different own res*.php files
- within your Internet Browser the follwing Cookies are set and valid for one hour
- BYOCAR_ACCESSTOKEN => this is the Access Token to request data, i. e. you do not need to go back to step 1 everytime
- state => this is the product which you have requested in step 1, e. g. "electric vehicle status" in our example
- vin => not necessary just for test cases
- debug => true / false

- if you face errors you may use the SetDebugTrue.php => the the Cookie is set an more data is written in every page to easen error handling
- sure, SetDebugFalse.php make the opposite 






