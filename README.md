#URL_Shortener

To see this in action, please go to [http://twayd.com](http://twayd.com).

##How It Works (Briefly)
URLs are stored in a database and are associated with an unique numerical id that functions as a primary key. This id is in decimal (base 10) format, but it can be more succinctly represented in a higher base. PHP is able to convert to base 36, which uses all the numerical digits as well as the lating alphabet \[0-9,A-Z\].

Urls are therefore associated with the base 36 representation of their numerical primary key, allowing them to be represented in a concise manner.

#API Endpoint
`http://twayd.com`

##Summary Of Resource URL Patterns
`/url` takes a hash as input and converts it to the original url

`/hash` - takes a url as input and hashes it into a shortened url.
    
##Example Usage - Request The Original Url

* `curl http://twayd.com/url?hash=4`
    * `hash` - [REQUIRED] - the shortened url hash. Either the full hash url (e.g. http://twayd.com/4) or just the hash (e.g. 4) need be given.

* **return value**: A JSon string called `result` with a key `originalUrl` if successful. The http status code will be set to 200.
* **errors**: If url hash does not exist, then error message is returned. The http status code will be set to 403.

##Example Usage - Convert An Url To A Hash

* `curl --data "url=news24.com" http://twayd.com/hash`
    * `data` - [REQUIRED] - the original url to be shortened. Note that this is a post method

* **return value**: A JSon string called `result` with a key `hashedUrl`. The http status code will be set to 200.
* **errors**: If url already exists as a hash, then an error message is returned stating this. The `hashedUrl` will still be returned.

##Example Usage - Obtain A Hash Given The Original Url
* `curl http://twayd.com/hash?url=hootsuite.com`
    * `url` - [REQUIRED] - the original url that was shortened. Note that this is a get method

* **return value**: A JSon string called `result` with a key `hashedUrl`. The http status code will be set to 200.
* **errors**: If url does not exist as a hash, then an error message is returned stating this.

