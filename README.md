## About Voucher API

Features

- Create Voucher
- Read Voucher
- Update Voucher
- Delete Voucher
- Register
- Login
- Logout


## Installation 

- Clone repo https://github.com/killerboduk1/voucher.git
- cd to voucher
- Install the App `composer install`
- Migrate the database `php artisan migrate`
- run the application `php artisan serve`

## Register

- api/register
  - method POST
  - Bearer $token
  - Header Accept application/json
  - request body
 ```json
{
  "username": "test",
  "first_name": "test",
  "email": "test@email.com",
  "password": "123123qa",
  "password_confirmation": "123123qa"
}
```
- api/login 
  - method POST
  - Bearer $token 
  - Header Accept application/json
  - request body
 ```json
{
  "username": "testing",
  "password": "123123qa"
}
```
- api/logout
  - method POST
  - Bearer $token
  - Header Accept application/json
- api/vouchers 
    - get all voucher
    - method GET|HEAD
    - Bearer $token
    - Header Accept application/json
- api/vouchers/{voucher}
    - get voucher
    - method GET|HEAD
    - Bearer $token
    - Header Accept application/json
- api/vouchers
    - create voucher
    - method POST
    - Bearer $token
    - Header Accept application/json
    - request body
 ```json
{
  "voucher": "abc01"
}
```
- api/vouchers/{voucher}
    - update voucher
    - method PUT|PATCH
    - Bearer $token
    - Header Accept application/json
    - request body
 ```json
{
  "voucher": "abc02"
}
```
- api/vouchers/{voucher}
    - delete voucher
    - method DELETE
    - Bearer $token
    - Header Accept application/json