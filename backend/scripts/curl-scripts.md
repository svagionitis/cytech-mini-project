# Command line REST requests using curl

## GET requests

### GET request with all the rows of the table

curl -X GET 'http://localhost/cytech/user'

### GET request with paging

curl -X GET 'http://localhost/cytech/user?page=1&limit=3'

### GET request with sorting

curl -X GET 'http://localhost/cytech/user?sort_by=LastName&order_by=DESC'

### GET request with filtering

curl -X GET 'http://localhost/cytech/user?LastName=Vagionitis'

### GET request combining all the above

curl -X GET 'http://localhost/cytech/user?page=1&limit=3&sort_by=LastName&order_by=DESC&TravelDateStart=2020-09-21'


## POST requests

curl -X POST -d "{\"FirstName\":\"Pantelis\", \"LastName\":\"Thalassinos\", \"Email\":\"pantelis.thalassinos@kriti.tv\", \"TravelDateStart\":\"2020-09-21\", \"TravelDateEnd\":\"2020-09-27\", \"TravelReason\":\"Mpla Mpla Mpla\"}" http://localhost/cytech/user


## PUT requests

curl -X PUT -d "{\"FirstName\":\"Pantelis\", \"LastName\":\"Thalassinos\", \"Email\":\"pantelis.thalassinos@kriti.tv\", \"TravelDateStart\":\"2020-09-21\", \"TravelDateEnd\":\"2020-09-27\", \"TravelReason\":\"Mpla Mpla Mpla Mplou Mplou Mplou\"}" http://localhost/cytech/user/userid/11


## DELETE requests

curl -X DELETE http://localhost/cytech/user/userid/11