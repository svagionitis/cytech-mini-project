# Cytech Mini Project

## What is this

This is a simple page which shows some user data in a table and can perform
* paging the data with some predefened options (10, 25, 50, 100)
* sorting in each column
* searching in each column (this is a regex search)
* searching in all columns (this is an exact match search)


## Technologies Used

This project was implemented on a Windows 10 laptop using the [XAMPP PHP development environment](https://www.apachefriends.org/index.html). In detail, the version [v7.4.10](https://www.apachefriends.org/blog/new_xampp_20200912.html) was used. This version includes the following
* PHP 7.4.10
* Apache 2.4.46
* MariaDB 10.4.14
* Perl 5.32.0
* phpMyAdmin 5.0.2

The backend was implented in PHP without any external PHP development framework. It just serves a REST API to be consumed by other clients or frontends.

The frontend was implemented in HTML/CSS/Javascript using the [DataTables](https://datatables.net/) which is a plug-in for [jQuery](https://jquery.com/) library.


## Backend

### How to run

1. In the document root of the HTTP server create a directory called `cytech` and copy all the files located on the `backend` directory there.
2. Run the SQL query `backend\scripts\cytech.sql` in order to create the `cytech` database with the `user` table.
3. Create a user in the database with username `cytech` and password `cytech@1234!` and grant full access to the `cytech` database.
4. Run the REST API call `http://localhost/cytech/user?generate_users=1000` to generate 1000 users in the database for example.

### Database Schema

There is only one table in the database. Next following an SQL query which creates the table

```
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `UserID` int(10) UNSIGNED NOT NULL,
  `FirstName` varchar(30) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `TravelDateStart` date NOT NULL,
  `TravelDateEnd` date NOT NULL,
  `TravelReason` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

There is a file, `backend\scripts\cytech.sql`, which can create a database called `cytech` with a table `user`.

### PHP files

The tree structure is the following

```
.
├── api
│   ├── config
│   │   └── DatabaseConnector.php : The connection with the database
│   ├── controller
│   │   └── UserController.php    : Handle of the REST API calls
│   └── gateway
│       └── UserGateway.php       : Handle of the SQL queries to the database
├── index.php                     : Starting point of PHP
├── .htaccess                     : A Web Server configuration file for the backend
└── scripts
    ├── curl-scripts.md           : curl scripts for the REST API
    └── cytech.sql                : SQL scripts to create the database
```

### REST API calls

There is a file `backend\scripts\curl-scripts.md` which describes the REST API calls. This is not the best way to describe the API calls, but it's good for now.

### REST API calls response

When successful a JSON is returned with the following values:

* `start`: The number of rows to skip.
* `length`: The number of rows to be returned.
* `data`: An array of the values which retrieved by the database depending of the input parameters, like sorting, searching etc.
* `recordsTotal`: Total records, before filtering (i.e. the total number of records in the database).
* `recordsFiltered`: Total records, after filtering (i.e. the total number of records after filtering has been applied - not just the number of records being returned for this page of data).

Next following an example

```
{
  "data":[...],
  "start":"0",
  "length":"10",
  "recordsTotal":"112",
  "recordsFiltered":"112"
}
```

If there is an error, then a JSON is returned with an error field like the following

```
{
  "error": "Not found",
}
```

## Frontend

### How to run

Open the file `user.html` in a browser and check the data which are populated on the table. In order to add data, use the `generate_users` REST API call.

You can search each column, at the bottom of each column, using regex values, like show all the names which start with S which the regex is `^S`.

You can search all the columns, at the top right above the table, using an exact match.

You can sort each column with the arrows next to the header cell of each column.


## TODO

There are many things still to do on this project

* There is no proper documentation for the REST API calls with the responses.
* There is no unit testing and testing of the REST API calls.
* The PHP code needs lot's of refactoring.
* Need a better way to automate the creation of the database and the population with data.
* Did I mention testing? :)
