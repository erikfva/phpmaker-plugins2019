## Use

### Login

First you need get session_key code

```javascript
//POST url: localhost/medical/login.php
//variables:
//    username: (account name)
//    password: (account password)
//    cmdx: login,webservice
var urlencoded = new URLSearchParams();
urlencoded.append("cmdx", "login,webservice");
urlencoded.append("username", "erick");
urlencoded.append("password", "arma");

var requestOptions = {
  method: "POST",
  body: urlencoded,
  redirect: "follow",
};

fetch("localhost/medical/login.php", requestOptions)
  .then((response) => response.text())
  .then((result) => console.log(result))
  .catch((error) => console.log("error", error));
```

### Get/Post Data

Add cmdx, session_key variables in GET or POST request

```
GET: http://localhost/medical/consultalist.php?cmdx=webservice&session_key=ljpm0elajlehj8r0poo3tggjdk
```
