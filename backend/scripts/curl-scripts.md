# Command line REST requests using curl

## GET requests

curl -X GET http://localhost/cytech/user

curl -X GET http://localhost/cytech/user/userid/11

curl -X GET http://localhost/cytech/user/email/foo.bar@foobar.tv

### GET request with offset and limit

curl -X GET http://localhost/cytech/user?offset=0&limit=3


## POST requests

curl -X POST -d "{\"FirstName\":\"Pantelis\", \"LastName\":\"Thalassinos\", \"Email\":\"pantelis.thalassinos@kriti.tv\", \"TravelDateStart\":\"2020-09-21\", \"TravelDateEnd\":\"2020-09-27\", \"TravelReason\":\"Mpla Mpla Mpla\"}" http://localhost/cytech/user


## PUT requests

curl -X PUT -d "{\"FirstName\":\"Pantelis\", \"LastName\":\"Thalassinos\", \"Email\":\"pantelis.thalassinos@kriti.tv\", \"TravelDateStart\":\"2020-09-21\", \"TravelDateEnd\":\"2020-09-27\", \"TravelReason\":\"Mpla Mpla Mpla Mplou Mplou Mplou\"}" http://localhost/cytech/user/userid/11


## DELETE requests

curl -X DELETE http://localhost/cytech/user/userid/11