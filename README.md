# Introduction
This guide covers the integration of Simplepayment.

> Last update: 2018-03-27

# General Flow

1. Create a payment order to get a transaction id in return
2. redirect to a specified url or pass the transaction id into sdk
3. User can complete the payment according to the instructions
4. Payment notification will be sent if the charging is successful

# Web services Information

We are using RESTful API, and data format is JSON.

### URL:

Will be provided together with appid and secret key.

## Creating a payment order

### Request:
 
#### Endpoint: /api/v1/create

#### Request parameters ( in json ) :

| Name | Type | Length | Requirement | Description |
| -----| -----|-----| ---- | ---- |
| timestamp | bigint | - | Mandatory | unix time stamp, also known as POSIX time / epoch time, please synchronize your system clock with NTP pool  |
| user_id | string | 40 | Mandatory | ID of your user, can be email or running numbers |
| merchant\_transaction\_id | string | 128 | Mandatory | Merchant transaction identifier, must be unique for every order |
| transaction_description | string | 256 | Optional | A short description about this order |
| payment_channel | string | 30 | Mandatory | Payment channel of this order, full list is in Appendix section |
| currency | string | 3 | Mandatory | MYR, IDR, VND depending on the country of the payment channel|
| amount | integer | - | Mandatory | The price which user should pay |
| item_id | string | 40 | Optional | Item identifier, useful when you have different items with same price tab |
| item_name | string | 40 | Mandatory | The name of the item, it will be displayed in payment page or SMS |
| redirect_url | string | - | Optional | The url which the user will be redirected to after the payment, our id and payment status code will be added into the url, check Appendix section for details |
| redirect_target | string | - | Optional | the default is "_top" |
| custom | text | - | Optional | Extra identifier you wish to get in payment notification |

#### HTTP Headers:

| Name | Description |
| ---- | ----------- |
| Content-Type | application/json, The type of request content |
| AppId | Your **AppId** |
| Bodysign | Hash of request messsage, Appendix section will cover the calculation. |

### Response:

#### HTTP Headers: 

| Name | Description |
| ---- | ----------- |
| Content-Type | application/vnd.api+json |
| AppId | Your **AppId** |
| Bodysign | Hash of responded json, Appendix section will cover the calculation. |

You can identify if the request is successful by checking the root of the json. Successful case always use 'data' as root, while failed case is always 'errors'.

#### Body - Successful:

| Name | Description |
| ---- | ----------- |
| id | Our transaction id, only valid for 10 minutes. |
| status | The status code of this order |
| detail | The message of the status |
| href | The url to redirect |

> example:
>
>> json:
>>"{"data":{"id":"595962b31bc7e0056a5051ba","timestamp":1499030195,"status":"ORDER_CREATED","status_code":200,"title":"Order Created.","detail":"Payment order has been created.","links":{"href":"http:\/\/payment.simplepayment.solutions\/order\/595962b31bc7e0056a5051ba","rel":"self","method":"GET"}}}"
}

#### Body - Error:

| Name | Description |
| ---- | ----------- |
| id | error code, check appendix for the full list |
| title | The short description of error |
| detail | The description of error |
| code | http status code |

>example:
>
> >{"errors":{"id":"INVALID\_APP\_ID","title":"The App id in request header is invalid.","detail":"The App id or client account is not found or has been deactivated.","code":400,"links":{"about":""}}}

## Payment Notification

Upon successful payment, payment notification will be sent to the url you configured in our backend via POST method.

### Request json

| Name | Description |
| ---- | --- |
| id | Our transaction identifier |
| timestamp | unix timestamp |
| details | array of transaction details |
 
### Array of transaction details

| Name | Description |
| ---- | --- |
| app_id | your AppId |
| user_id | User identifier, same as the one in order creation |
| merchant\_transaction_id | Your transaction id, same as the one in payment order
| transaction_description | Description of this transaction, if there is any |
| payment_channel | Payment channel that user used to complete this order |
| currency | ISO 4217 Currency Codes, [click here](http://www.xe.com/iso4217.php) for the complete list |
| amount | The total amount that user paid |
| status_code | The status of the transaction, Appendix section has the complete list |
| status | Short description of status_code. |
| item_id | Your item identifier |
| item_name | Your item name |
| custom | the extra information submitted during order creation |

## Order checking

### Request:

#### Endpoint: api/v1/check_order

#### Request parameters ( in json ) :

| Name | Type | Length | Requirement | Description |
| -----| -----|-----| ---- | ---- |
| id | string | - | Mandatory | our transaction id |

#### HTTP Headers:

| Name | Description |
| ---- | ----------- |
| Content-Type | application/json, The type of request content |
| AppId | Your **AppId** |
| Bodysign | Hash of request messsage, Appendix section will cover the calculation. |

### Response ( in Json ):

#### HTTP Headers: 

| Name | Description |
| ---- | ----------- |
| Content-Type | application/vnd.api+json |
| AppId | Your **AppId** |
| Bodysign | Hash of responded json, Appendix section will cover the calculation. |

#### Body - Successful

| Name | Description |
| ---- | --- |
| app_id | your AppId |
| user_id | User identifier, same as the one in order creation |
| merchant\_transaction_id | Your transaction id, same as the one in payment order
| transaction_description | Description of this transaction, if there is any |
| payment_channel | Payment channel that user used to complete this order |
| currency | ISO 4217 Currency Codes, [click here](http://www.xe.com/iso4217.php) for the complete list |
| amount | The total amount that user paid |
| status_code | The status of the transaction, Appendix section has the complete list |
| status | Short description of status_code. |
| item_id | Your item identifier |
| item_name | Your item name |
| custom | the extra information submitted during order creation |

#### Body - Error:

| Name | Description |
| ---- | ----------- |
| id | error code, [click here] for the full list |
| title | The short description of error |
| detail | The description of error |
| code | http status code |

# Appendix

## Payment channel

| Name | Description | Amount range |
| --- | --- | --- |
| telkomsel_airtime | Airtime, Indonesia | IDR5.000, IDR10.000, IDR25.000, IDR50.000, IDR100.000 |
| indosat_airtime | Airtime, Indonesia | IDR5.000, IDR10.000, IDR25.000, IDR50.000, IDR100.000 |
| xl_airtime | Airtime, Indonesia | IDR5.000, IDR10.000, IDR25.000, IDR50.000, IDR100.000 |
| three_airtime | Airtime, Indonesia | IDR5000, IDR10000, IDR25.000, IDR50.000, IDR100.000 |
| alfa_otc | Over the counter of convenience store Alfamart, Indonesia | IDR10000 ~ Rp1.000.000 |
| indomaret_otc | Over the counter of convenience store Indomaret, Indonesia | IDR10000 ~ Rp1.000.000 |
| gopay_wallet | GoPay Wallet, Indonesia | IDR10.000 ~ IDR100.000 |
| doku_wallet | Doku Wallet, Indonesia | IDR10.000 ~ IDR100.0000 |
| celcom_airtime | Airtime, Malaysia | MYR3, MYR20, MYR10, MYR20, MYR50 |
| digi_airtime | Airtime, Malaysia | MYR3, MYR5, MYR10, MYR20, MYR50 |
| maxis_airtime | Airtime, Malaysia | MYR3, MYR5, MYR10, MYR20, MYR50 |
| meps | Online Banking, Malaysia | MYR50 ~ MYR1000 |
| all | All payment channels, display selections according to amount |
| default | All non-voucher payment channels, display selections according to amount |
| bank_transfer | All bank transfer channels, display selections according to amount, IDR50.000 ~ IDR20.000.000 |
| airtime | All airtime channels, display selections according to amount |
| wallet | All wallet channels, display selections according to amount |
| voucher | All voucher channels, display selections according to amount |
| airtime_testing | **For testing ONLY, please ignore any payment notification with such value.** To test: phoe number: 088881234567, otp : 1234 | IDR10.000, IDR20.000, IDR25.000, IDR50.000, IDR100.000 |	

## Bodysign

To get the signature, take these steps:

1. base64 encode the json
> example:
> 
>> json: {"timestamp":1498954516,"amount":10000.00,"user_id":"test_user","item_name":"2000 coins","payment\_channel":"telkomsel\_airtime","redirect_url":"http:\/\/192.168.56.105\/callback\/payment","redirect_target":"\_top","merchant_transaction_id":"1498954516427118"}
>> base64 : eyJ0aW1lc3RhbXAiOjE0OTg5NTQ1MTYsImFtb3VudCI6MTAwMDAsInVzZXJfaWQiOiJ0ZXN0X3VzZXIiLCJpdGVtX25hbWUiOiIyMDAwIGNvaW5zIiwicGF5bWVudF9jaGFubmVsIjoidGVsa29tc2VsX2FpcnRpbWUiLCJyZWRpcmVjdF91cmwiOiJodHRwOlwvXC8xOTIuMTY4LjU2LjEwNVwvY2FsbGJhY2tcL3BheW1lbnQiLCJyZWRpcmVjdF90YXJnZXQiOiJfdG9wIiwibWVyY2hhbnRfdHJhbnNhY3Rpb25faWQiOiIxNDk4OTU0NTE2NDI3MTE4In0=

2. Calculate the hash using HMAC-SHA-256, using the **SecretKey** 
3. > example:
> 
>> signature : 02978711eb15f52fb2d1c222c056293fd6f0348e28f6b8960d021e142c3a932f

## Redirect Url

Please avoid using 'transaction\_id' and 'status_code' in your redirect url, as they will be added into your redirect url.

>example:
>
>> https://www.domain.com/callback?transaction\_id=5a586c21eed56c65b370bd74& status\_code=PAYMENT_PENDING
>> 
>> https://www.domain.com/callback?param1=param1&param2=param2&transaction\_id=5a586c21eed56c65b370bd74& status\_code=PAYMENT_PENDING



## Payment status

Failed payment status will be updatedwhile ($i <= 10) { as accurate as possbile, depending on upstream service provider.

| Name | Description |
| ---- | --- |
| PAYMENT_COMPLETED | This payment has been completed successfully, this is the **ONLY** payment status you should accept as payment successful. |
| ORDER_CREATED | Payment order has been created. |
| PAYMENT_PENDING | Waiting for user to complete the paymnet. |
| PAYMENT_FAILED | User does not complete the payment. |
| USED\_VOUCHER | This voucher code has been used. |
| INVALID\_VOUCHER | This voucher code is wrong. |
| PAYMENT\_REVERSED | Payment was reversed, timeout at switching network. |
| VERIFICATION\_FAILED | Verification is not successul. |
| CHANNEL\_NOT_AVAILABLE | Payment channel provider is unreachable. |
| INSUFFICIENT\_BALANCE | Not enough balance to compelete the payment. |



 