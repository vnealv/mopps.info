FORMAT: 1A

# MOPPS-API

# Authentication
User authentication.

## Login user [POST /api/auth/login]
Login a user with a `email` and `password`.

+ Request (application/json)
    + Body

            {
                "data": {
                    "email": "foo@test.my",
                    "password": "bar"
                }
            }

+ Response 200 (application/json)
    + Body

            {
                "message": "success",
                "errors": [],
                "status_code": 200,
                "fail": 0,
                "return": {
                    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEyNDMsImlzcyI6Imh0dHA6XC9cL2xvY2FsaG9zdFwvbW9wcHMuaW5mb1wvcHVibGljXC9hcGlcL2F1dGhcL2xvZ2luIiwiaWF0IjoxNDY5OTc2ODM1LCJleHAiOjE0Njk5ODA0MzUsIm5iZiI6MTQ2OTk3NjgzNSwianRpIjoiMGE4YWIyY2I2ZjYxMTEwNTk1NGZjNzM1NDk1ZGUwYmMifQ.yXfbYM2-gGQ-onuhsDvS82u_FJakKA0ehx6BbVNiOf4"
                }
            }

## Register user [POST /api/auth/signup]
Register a new user with a `email` and `password`.

+ Request (application/json)
    + Body

            {
                "data": {
                    "email": "foo@test.my",
                    "password": "bar"
                }
            }

+ Response 200 (application/json)
    + Body

            {
                "message": "success",
                "errors": [],
                "status_code": 200,
                "fail": 0,
                "return": {
                    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEyNDQsImlzcyI6Imh0dHA6XC9cL2xvY2FsaG9zdFwvbW9wcHMuaW5mb1wvcHVibGljXC9hcGlcL2F1dGhcL3NpZ251cCIsImlhdCI6MTQ2OTk3NzM4MywiZXhwIjoxNDY5OTgwOTgzLCJuYmYiOjE0Njk5NzczODMsImp0aSI6ImNmMzIzMGJjMzhiMDc1MjE4NmYwOGFlN2UwZTdjYjQ3In0.t9xTP36okeD4dKg1TpOcDLF4kr8WrBYEL9nUq6jv0jA"
                }
            }

## Password recovery [POST /api/auth/recovery]
Forgot your password. to recover account with `email` .

+ Request (application/json)
    + Body

            {
                "data": {
                    "email": "foo@test.my"
                }
            }

+ Response 500 (application/json)
    + Body

            "under maintenance"

## Password reset [POST /api/auth/reset]
Reset user password with `email`, `password`, `password_confirmation` and `token` .

+ Request (application/json)
    + Body

            {
                "data": {
                    "email": "foo@test.my",
                    "password": "bar",
                    "password_confirmation": "bar",
                    "token": "1AaksjhdkASDASDJ123kjn12kjh"
                }
            }

+ Response 500 (application/json)
    + Body

            "under maintenance"

# Profile
User profile.

## User Profile `PROTECTED` [POST /api/profile]
Get user profile with `token`.

+ Request (application/json)
    + Headers

            Authorization: Bearer {token_goes_here}
    + Body

            {
                "data": []
            }

+ Response 200 (application/json)
    + Body

            {
                "message": "success",
                "errors": [],
                "status_code": 200,
                "fail": 0,
                "return": [
                    {
                        "id": 1237,
                        "user_id": "PPS01591999",
                        "public_user_id": "SP047812",
                        "name": "munfai L",
                        "email": "a@a.com",
                        "usergroup": "user",
                        "photo_id": "EA8EC494-4605-4F9B-B287-C8BDA04C2D84",
                        "status": "active",
                        "phone_number": "0162782984",
                        "joined_unix": null,
                        "last_login": "1467736421",
                        "isPhoneVerified": "0",
                        "isEmailVerified": "0",
                        "isResetPassword": "0",
                        "created_at": null,
                        "updated_at": null,
                        "vehicles": [
                            {
                                "id": 2,
                                "brand": "Perodua",
                                "model": "Myvi",
                                "color": "Yellow",
                                "vehicle_number": "W1234A",
                                "user_id": "1237",
                                "photo_id": "010CFAA1-C87C-4698-AE87-CF7D73FAFF9B"
                            },
                            {
                                "id": 3,
                                "brand": "Proton",
                                "model": "Wira",
                                "color": "Maroon",
                                "vehicle_number": "BMM1234",
                                "user_id": "1237",
                                "photo_id": "12A7EBFA-6AF7-4AFD-9DD2-91EA1923B316"
                            },
                            {
                                "id": 4,
                                "brand": "Toyota",
                                "model": "Vios",
                                "color": "White",
                                "vehicle_number": "AMD9989",
                                "user_id": "1237",
                                "photo_id": "59D5E33C-2327-49B6-8B5F-266AC7574AD9"
                            }
                        ]
                    }
                ]
            }

## Update User Profile `PROTECTED` [POST /api/profile/update]
Update user profile with `token`.
`name`, `phone_number`, `password`, `photo`

+ Parameters
    + name: (string, optional) - Name field in the user profile
    + phone_number: (string, optional) - User phone number
    + password: (string, optional) - User new password
    + photo: (string, optional) - TO BE DISCUSSED

+ Request (application/json)
    + Headers

            Authorization: Bearer {token_goes_here}
    + Body

            {
                "data": {
                    "param_field_name": "param_value"
                }
            }

+ Response 200 (application/json)
    + Body

            {
                "message": "success",
                "errors": [],
                "status_code": 200,
                "fail": 0,
                "return": {
                    "updated": true
                }
            }

# Account
User account.

## Topup Account Credit `PROTECTED` [POST /api/account/add]
Add credit to user profile with `token`.
`amount`, `brain_token`

+ Parameters
    + amount: (float, required) - The amount to be added
    + brain_token: (string, optional) - TO BE DISCUSSED

+ Request (application/json)
    + Headers

            Authorization: Bearer {token_goes_here}
    + Body

            {
                "data": {
                    "amount": "22.50"
                }
            }

+ Response 200 (application/json)
    + Body

            {
                "message": "success",
                "errors": [],
                "status_code": 200,
                "fail": 0,
                "return": {
                    "updated": true
                }
            }

## Get Account Credit `PROTECTED` [POST /api/account/add]
Retrieve credit balance for a user with `token`.

+ Request (application/json)
    + Headers

            Authorization: Bearer {token_goes_here}
    + Body

            {
                "data": []
            }

+ Response 200 (application/json)
    + Body

            {
                "message": "success",
                "errors": [],
                "status_code": 200,
                "fail": 0,
                "return": {
                    "id": 9,
                    "user_id": "1243",
                    "balance": "22.5",
                    "created_at": "2016-08-01 10:04:40",
                    "updated_at": "2016-08-01 10:04:40"
                }
            }

# Vehicle
User Vehicle.

## Add a vehicle`PROTECTED` [POST /api/vehicle/add]
Add vehicle to user profile with `token`.
`brand`, `model`, `color`, `vehicle_number`

+ Parameters
    + brand: (string, required) - Vehicle brand
    + model: (string, required) - Vehicle model
    + color: (string, required) - Vehicle color
    + vehicle_number: (string, required) - Vehicle license number

+ Request (application/json)
    + Headers

            Authorization: Bearer {token_goes_here}
    + Body

            {
                "data": {
                    "brand": "Proton",
                    "model": "Satria",
                    "color": "yellow",
                    "vehicle_number": "WJA2697"
                }
            }

+ Response 200 (application/json)
    + Body

            {
                "message": "success",
                "errors": [],
                "status_code": 200,
                "fail": 0,
                "return": {
                    "inserted": true
                }
            }

## list of vehicle(s)`PROTECTED` [POST /api/vehicle]
show vehicles listed under user profile with `token`.
`id` if no id present then it will show all the registered vehicles under this user.

+ Parameters
    + id: (string, optional) - Vehicle id

+ Request (application/json)
    + Headers

            Authorization: Bearer {token_goes_here}
    + Body

            {
                "data": {
                    "id": "14"
                }
            }

+ Response 200 (application/json)
    + Body

            {
                "message": "success",
                "errors": [],
                "status_code": 200,
                "fail": 0,
                "return": [
                    {
                        "id": 14,
                        "brand": "Proton",
                        "model": "Satria",
                        "color": "yellow",
                        "vehicle_number": "WJA2697",
                        "user_id": "1243",
                        "photo_id": null,
                        "created_at": "2016-08-02 05:28:49",
                        "updated_at": "2016-08-02 05:40:07",
                        "deleted_at": null
                    }
                ]
            }

## Update vehicle information`PROTECTED` [POST /api/vehicle/update]
update vehicle information with `token`.
`id`, `brand`, `model`, `color`, `vehicle_number`

+ Parameters
    + id: (string, required) - Vehicle id
    + brand: (string, optional) - Vehicle brand
    + model: (string, optional) - Vehicle model
    + color: (string, optional) - Vehicle color
    + vehicle_number: (string, optional) - Vehicle license number

+ Request (application/json)
    + Headers

            Authorization: Bearer {token_goes_here}
    + Body

            {
                "data": {
                    "id": "14",
                    "brand": "Proton",
                    "model": "Satria",
                    "color": "green",
                    "vehicle_number": "WJA2697"
                }
            }

+ Response 200 (application/json)
    + Body

            {
                "message": "success",
                "errors": [],
                "status_code": 200,
                "fail": 0,
                "return": {
                    "updated": true
                }
            }

## delete vehicle `PROTECTED` [POST /api/vehicle/delete]
delete vehicle  with `token`.
`id`

+ Parameters
    + id: (string, required) - Vehicle id

+ Request (application/json)
    + Headers

            Authorization: Bearer {token_goes_here}
    + Body

            {
                "data": {
                    "id": "14"
                }
            }

+ Response 200 (application/json)
    + Body

            {
                "message": "success",
                "errors": [],
                "status_code": 200,
                "fail": 0,
                "return": {
                    "deleted": true
                }
            }

# PSession
Parking Session.

## Add a Parking Session`PROTECTED` [POST /api/psession/add]
Add a parking session with `token`.
`vehicle_id`, `start_unixtime`, `parking_duration`, `end_unixtime`, `parking_id`, `amount`, `latitude`, `longitude`, `confirmation`

+ Parameters
    + vehicle_id: (string, required) - Vehicle id
    + start_unixtime: (string, required) - Parking start time.
    + parking_duration: (string, required) - Parking duration.
    + end_unixtime: (string, required) - parking end time.
    + parking_id: (string, required) - Which parking place is it
    + amount: (string, required) - Amount of credit to be deducted.
    + latitude: (string, required) - latitude
    + longitude: (string, required) - longitude
    + confirmation: (string, optional) - this field is used in the case of duplication or editing an old session.
        + Default: new OR update

+ Request (application/json)
    + Headers

            Authorization: Bearer {token_goes_here}
    + Body

            {
                "data": {
                    "vehicle_id": "16",
                    "start_unixtime": "1470448800",
                    "parking_duration": "1.75",
                    "end_unixtime": "1470455100",
                    "parking_id": "1",
                    "amount": "1.05",
                    "latitude": "WJA2697",
                    "longitude": "WJA2697",
                    "confirmation": "update"
                }
            }

+ Response 200 (application/json)
    + Body

            {
                "message": "success",
                "errors": [],
                "status_code": 200,
                "fail": 0,
                "return": {
                    "created": true
                }
            }

## Get parking spots list`PROTECTED` [POST /api/parking]
Get parking list with `token`.

+ Request (application/json)
    + Headers

            Authorization: Bearer {token_goes_here}
    + Body

            {
                "data": []
            }

+ Response 200 (application/json)
    + Body

            {
                "message": "success",
                "errors": [],
                "status_code": 200,
                "fail": 0,
                "return": [
                    {
                        "id": 1,
                        "location_name": "Puchong",
                        "street": "Jalan Puchong",
                        "city": "Puchong",
                        "state": "Selangor",
                        "country": "Malaysia",
                        "zip_code": "47120",
                        "latitude": "3.0018988",
                        "longitude": "101.5569429",
                        "rate_per_hour": "0.60",
                        "area_code": "PUC",
                        "created_at": null,
                        "updated_at": null,
                        "deleted_at": null
                    }
                ]
            }

## Get active parking session`PROTECTED` [POST /api/psession/active]
Get active parking session with `token`.

+ Request (application/json)
    + Headers

            Authorization: Bearer {token_goes_here}
    + Body

            {
                "data": []
            }

+ Response 200 (application/json)
    + Body

            {
                "message": "success",
                "errors": [],
                "status_code": 200,
                "fail": 0,
                "return": [
                    {
                        "id": 44,
                        "user_id": "1243",
                        "vehicle_id": "16",
                        "isActive": "1",
                        "start_unixtime": "1470808800",
                        "parking_duration": "2.00",
                        "end_unixtime": "1470816000",
                        "parking_id": "1",
                        "amount": "1.20",
                        "latitude": "WJA2697",
                        "longitude": "WJA2697",
                        "vehicle_number": "WJA2697",
                        "created_at": "2016-08-10 06:17:56",
                        "updated_at": "2016-08-10 06:17:56",
                        "deleted_at": null
                    }
                ]
            }

# AppApiV1ControllersHistoryController

## Get user history `PROTECTED` [POST /api/history]
Get user history with `token`.

+ Request (application/json)
    + Headers

            Authorization: Bearer {token_goes_here}
    + Body

            {
                "data": []
            }

+ Response 200 (application/json)
    + Body

            {
                "message": "success",
                "errors": [],
                "status_code": 200,
                "fail": 0,
                "return": [
                    {
                        "id": 23,
                        "user_id": "1243",
                        "type": "TopUp",
                        "amount": "10",
                        "date_unix": "1470816008",
                        "vehicle_id": null,
                        "vehicle_number": null,
                        "parking_id": null,
                        "parking_name": null,
                        "surcharge": "0.00",
                        "gst": "0.00",
                        "tax": "0.00",
                        "total": "10",
                        "created_at": "2016-08-10 08:00:08",
                        "updated_at": "2016-08-10 08:00:08",
                        "deleted_at": null
                    },
                    {
                        "id": 24,
                        "user_id": "1243",
                        "type": "Parking",
                        "amount": "1.2",
                        "date_unix": "1470817230",
                        "vehicle_id": "16",
                        "vehicle_number": "WJA2697",
                        "parking_id": "1",
                        "parking_name": "Puchong",
                        "surcharge": "0.00",
                        "gst": "0.00",
                        "tax": "0.00",
                        "total": "1.2",
                        "created_at": "2016-08-10 08:20:30",
                        "updated_at": "2016-08-10 08:20:30",
                        "deleted_at": null
                    }
                ]
            }

# Photo
Photos.

## Add/Update user photo`PROTECTED` [POST /api/photo/user]
Add OR update user photo with `token`, `base64`.

+ Request (application/json)
    + Headers

            Authorization: Bearer {token_goes_here}
    + Body

            {
                "data": {
                    "base64": "akjshdkjashdkjashdkjahsdkjh"
                }
            }

+ Response 200 (application/json)
    + Body

            {
                "message": "success",
                "errors": [],
                "status_code": 200,
                "fail": 0,
                "return": {
                    "inserted": true
                }
            }

## Add/Update vehicle photo`PROTECTED` [POST /api/photo/vehicle]
Add OR update vehicle photo with `token`, `base64`, `vehicle_id` OR `vehicle_number`.

+ Request (application/json)
    + Headers

            Authorization: Bearer {token_goes_here}
    + Body

            {
                "data": {
                    "base64": "akjshdkjashdkjashdkjahsdkjh",
                    "vehicle_id": "16"
                }
            }

+ Response 200 (application/json)
    + Body

            {
                "message": "success",
                "errors": [],
                "status_code": 200,
                "fail": 0,
                "return": {
                    "inserted": true
                }
            }