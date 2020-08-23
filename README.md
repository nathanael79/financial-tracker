# Financial Tracker

This is the financial tracker API for app that helps users to track their daily / monthly expenses and incomes, thus ultimately helping them not to overspend or lose track of their finances. At the same time, the app needs to be able to inform the users what is the highest expense, what's left in a month, so that they can improve their expense and income pattern in the future.

# Requirements
-   PHP >= 7.2.5
-   BCMath PHP Extension
-   Ctype PHP Extension
-   Fileinfo PHP extension
-   JSON PHP Extension
-   Mbstring PHP Extension
-   OpenSSL PHP Extension
-   PDO PHP Extension
-   Tokenizer PHP Extension
-   XML PHP Extension
- Webserver
- MySQL

# Installation

 - Clone this project to your local computer
 - Use `composer install` inside cloned project to install the vendor
 - Copy paste `.env.example` to `.env`, dont forget to setup `.env` according to your environment like database connection, app_debug, app_url and app.env
 - Run migration + seeder using this command: `php artisan migrate --seed`, it will create a database inside your DBMS and fill the user table with defined user by default.
 - Finally, you can run it using `php artisan serve` and it will show up with default url (http://localhost:8000)

# API Documentation
All of the API except Register & Login, need to bearer a token that created by system when you login as admin. You can login by default using this account information:

 - username: admin@admin.com
 - password: admin123
 
In this repository also contain postman collection to give you an easier way to test this API
 
## HTTP Status Code
 - 200 = OK
 - 500 = Failed
 - 404 = Data not found/empty
 - 403 = Forbidden
 - 422 = Unprocessable Entity

## AUTHENTICATION
### Register (http://localhost:8000/api/v1/register - POST)
| Field Name | Type  | Required | Description |
|--|--|--|--|
| name | string | Yes | - |
| email | email | Yes | - |
| password | string | Yes | - |

### Login (http://localhost:8000/api/v1/login - POST)
| Field Name | Type  | Required | Description |
|--|--|--|--|
| email | email | Yes | - |
| password | string | Yes | - |

## USER
### User Information (http://localhost:8000/api/v1/user - GET)

## FINANCIAL ACCOUNT
### Create a Financial Account (http://localhost:8000/api/v1/account/create - POST)
| Field Name | Type  | Required | Description |
|--|--|--|--|
| name | string | Yes | - |
| type | string | No | Default: null |
| limit | integer | No | Default:0 |

### Update a Financial Account (http://localhost:8000/api/v1/account/update/{id} - PUT)
| Field Name | Type  | Required | Description |
|--|--|--|--|
| name | string | No | - |
| type | string | No | Default: null |
| limit | integer | No | Default:0 |

### GET Financial Account (http://localhost:8000/api/v1/account/user-account/{id} - GET)

### GET Financial Account By USER (http://localhost:8000/api/v1/account/user-account - GET)

### GET ALL Financial Account (http://localhost:8000/api/v1/account/all - GET)

### Delete a Financial Account (http://localhost:8000/api/v1/account/delete/{id} - DELETE)

### Restore Deleted Financial Account (http://localhost:8000/api/v1/account/restore/{id} - GET)

## TRANSACTION
### Create a Transaction (http://localhost:8000/api/v1/transaction/create - POST)
| Field Name | Type  | Required | Description |
|--|--|--|--|
| financial_account_id | integer | Yes | - |
| title | string | Yes | - |
| description | text | No | - |
| amount | integer | Yes | - |
| type | enum: debit,credit | Yes | - |

### GET Transaction (http://localhost:8000/api/v1/transaction/{id} - GET)

### GET Transaction by Financial Account (http://localhost:8000/api/v1/transaction/account/{financialAccountId} - GET)

### GET Transaction by User(http://localhost:8000/api/v1/transaction/user/{userId} - GET)

### GET All Transaction (http://localhost:8000/api/v1/transaction/all - GET)

### Update a Transaction (http://localhost:8000/api/v1/transaction/{id} - PUT)
| Field Name | Type  | Required | Description |
|--|--|--|--|
| title | string | No | - |
| description | text | No | - |
| amount | integer | Yes | - |
| type | enum: debit,credit | Yes | - |

### Delete a Transaction (http://localhost:8000/api/v1/transaction/{id} - DELETE)

### Restore Deleted Transaction (http://localhost:8000/api/v1/transaction/restore/{id} - GET)

## REPORT/SUMMARY
### GET Report/Summary All Financial Account of USER (http://localhost:8000/api/v1/report/summary/{param} - GET)
Parameter (param) must be enum value: credit/debit by string

### GET Filtered Report/Summary All Financial Account of USER (http://localhost:8000/api/v1/report/{param} - GET)
Parameter (param) must be enum value: credit/debit by string

## SEARCH
### SEARCH DATA (http://localhost:8000/api/v1/search/{limit}/{page}/{offset} - GET)
| Field Name | Type  | Required | Description |
|--|--|--|--|
| keyword | string, integer | No | - |

This search function will look for all the column in Financial Account Table and Financial Account History Table

## Credits
 
Fullstack Developer - Imanuel Ronaldo (@nathanael79)
 
## License
 
The MIT License (MIT)

Copyright (c) 2020 Imanuel Ronaldo

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
