# Command line REST requests using curl

## GET requests

### GET request with all the rows of the table

`curl -X GET 'http://localhost/cytech/user'`

### GET request with paging

`curl -X GET 'http://localhost/cytech/user?start=0&lemgth=3'`

* `start`: The number of rows to skip (The equivalent of SQL OFFSET)
* `length`: The number of rows to fetch (The equivalent of SQL LIMIT)

### GET request with sorting

`curl -g -X GET 'http://localhost/cytech/user?columns[0][data]=UserID&order[0][column]=0&order[0][dir]=desc'`

* `columns[]`: An array with info for the columns. Here the `columns[0][data]` is used to specify which column
to use for sorting
* `order[]`: An array with the info to sort the data. The `order[0][column]` specify the index to the column to use
from the `columns[]` array. Here the 0 is specified. The `order[0][dir]` specify the direction of sorting, here is
descending. For ascending use `asc`.

The above fields sent by the server side of DataTables and more info can be found [here](https://datatables.net/manual/server-side).

### GET request with filtering for a specific column (regex search)

`curl -g -X GET 'http://localhost/cytech/user?columns[0][data]=UserID&columns[0][search][value]=9'`

* `columns[]`: An array with info for the columns. Here the `columns[0][data]` is used to specify which column
to use for searching and `columns[0][search][value]` the value to search this column.

`Note`: The per column search is a regex search and not an exact match.

### GET request with filtering in all columns (exact match search)

`curl -g -X GET 'http://localhost/cytech/user?search[value]=Minos'`

* `search[]`: An array with info for the searching in all the columns. Here the `search[value]` is specifying the
value to search.

`Note`: The search in all columns is an exact match search.

### GET request combining some of the above

`curl -g -X GET 'http://localhost/cytech/user?start=0&lemgth=3&columns[0][data]=UserID&columns[0][search][value]=9'`

### GET request to generate users

`curl -X GET 'http://localhost/cytech/user?generate_users=1000'`


## POST requests

`curl -X POST -d "{\"FirstName\":\"Minos\", \"LastName\":\"King\", \"Email\":\"minos.king@knossos.gr\", \"TravelDateStart\":\"2020-09-21\", \"TravelDateEnd\":\"2020-09-27\", \"TravelReason\":\"To Santorini for time off\"}" 'http://localhost/cytech/user'`


## PUT requests

`curl -X PUT -d "{\"FirstName\":\"Minos\", \"LastName\":\"King\", \"Email\":\"minos.king@kriti.gr\", \"TravelDateStart\":\"2020-09-21\", \"TravelDateEnd\":\"2020-09-27\", \"TravelReason\":\"To Santorini for time off\"}" 'http://localhost/cytech/user/userid/11'`


## DELETE requests

`curl -X DELETE 'http://localhost/cytech/user/userid/11'`