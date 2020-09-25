# Cytech Mini Project


## Technologies Used

This project was implemented on a Windows 10 laptop using the [XAMPP PHP development environment](https://www.apachefriends.org/index.html). In detail, the version [v7.4.10](https://www.apachefriends.org/blog/new_xampp_20200912.html) was used. This version includes the following
* PHP 7.4.10
* Apache 2.4.46
* MariaDB 10.4.14
* Perl 5.32.0
* phpMyAdmin 5.0.2

The backend was implented in PHP without any external PHP development framework.


## Backend

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

There is a file, `backend\scripts\cytech.sql`, which can create a database called `cytech` with a table `user`. There are some data to populate the table.

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

TBD
