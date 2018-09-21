https://i.imgur.com/cTxCynS.jpg

# Delivery App
It is a simple app to demonstrate a simple RESTful api with only index, create, update methods.
It uses google maps directions api to find distance of an order's origin and destination.

## Installation & Running
- Clone/Download the repository on a fresh ubuntu 18.04 x64
- Go to the project directory and open **docker-compose.yml** file.
- Add a new environment variable `GMAPS_API_KEY` under **services > app > environment** with the value as api key. (see screenshot for clarification) 
![Adding API Key](https://i.imgur.com/cTxCynS.jpg)
- Run setup.sh with sudo such as `sudo ./setup.sh`.
- It will automatically run few unit tests at the end of installation.
- If you want to run unit tests manually, you can do it by `docker-compose exec app vendor/bin/phpunit`

The test file is located at **tests > Feature > OrderTest.php**.

## Project Requirements
This app features three api endpoints.
- One endpoint to create an order (see sample)
    - To create an order, the API client must provide an origin and a destination (see sample)
    - The API responds an object containing the distance and the order ID (see sample)

- One endpoint to take an order (see sample)
    - An order must not be takable multiple time.
    - An error response should be sent if a client tries to take an order already taken.

- One endpoint to list orders (see sample)

## Samples
### Place order
  - Method: `POST`
  - URL path: `/order`
  - Request body:

    ```
    {
        "origin": ["START_LATITUDE", "START_LONGTITUDE"],
        "destination": ["END_LATITUDE", "END_LONGTITUDE"]
    }
    ```

  - Response:

    Header: `HTTP 200`
    Body:
      ```
      {
          "id": <order_id>,
          "distance": <total_distance>,
          "status": "UNASSIGN"
      }
      ```
    or

    Header: `HTTP 500`
    Body:
      ```json
      {
          "error": "ERROR_DESCRIPTION"
      }
      ```

### Take order

  - Method: `PUT`
  - URL path: `/order/:id`
  - Request body:
    ```
    {
        "status":"taken"
    }
    ```
  - Response:
    Header: `HTTP 200`
    Body:
      ```
      {
          "status": "SUCCESS"
      }
      ```
    or

    Header: `HTTP 409`
    Body:
      ```
      {
          "error": "ORDER_ALREADY_BEEN_TAKEN"
      }
      ```

### Order list

  - Method: `GET`
  - Url path: `/orders?page=:page&limit=:limit`
  - Response:

    ```
    [
        {
            "id": <order_id>,
            "distance": <total_distance>,
            "status": <ORDER_STATUS>
        },
        ...
    ]
    ```