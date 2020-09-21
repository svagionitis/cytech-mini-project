Command line REST requests using curl
=======================================


POST requests
----------------

curl -X POST -d "{\"FirstName\":\"Pantelis\", \"LastName\":\"Thalassinos\", \"Email\":\"pantelis.thalassinos@kriti.tv\", \"TravelDateStart\":\"2020-09-21\", \"TravelDateEnd\":\"2020-09-27\", \"TravelReason\":\"Mpla Mpla Mpla\"}" http://localhost/cytech/user


PUT requests
-------------

curl -X GET http://localhost/cytech/user/userid/11

curl -X PUT -d "{\"FirstName\":\"Pantelis\", \"LastName\":\"Thalassinos\", \"Email\":\"pantelis.thalassinos@kriti.tv\", \"TravelDateStart\":\"2020-09-21\", \"TravelDateEnd\":\"2020-09-27\", \"TravelReason\":\"Mpla Mpla Mpla Mplou Mplou Mplou\"}" http://localhost/cytech/user/userid/11


DELETE requests
----------------

curl -X DELETE http://localhost/cytech/user/userid/11