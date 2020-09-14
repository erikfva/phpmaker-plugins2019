# phpmaker-plugins

Non-intrusive add-on for PHPMaker.

## Requirements

#### PHPMaker 2020.0.14 or superior.

## Installation

### 1. Clone or copy repository into PHPMaker project folder.

Open console and goto inside PHPMaker project folder, then run command:

```Git
git clone https://github.com/erikfva/phpmaker-plugins2019.git phpmaker-plugins
```

### 2. Configure plugin.

Edit **phpmaker-plugins/phpfn.php** file and configure your main project namespace. You can find this information in the beginning of your **phpfn.php** file into main project folder.

#### <Project folder>/phpfn.php

```PHP
<?php

/**
 * PHPMaker Common classes and functions
 * (C) 2002-2019 e.World Technology Limited. All rights reserved.
*/
namespace PHPMaker2020\contab;
```

#### phpmaker-plugins/phpfn.php

```PHP
<?php
/** CONFIG YOUR MAIN PROJECT NAMESPACE HERE!!! **/
use PHPMaker2020\contab as phpfn;
/* **************************************** */
```

In this sample the namespace is **contab**.

### 3.- Configure your project:

--> Add to the beginning of **Server Events/Global/All Pages/Global Code**

```sh
	include_once "phpmaker-plugins/plgmngr.php";
```

--> Add to the beginning of **Server Events/Global/All Pages/Page_Head**

```sh
//incluyendo los encabezados de los "plugins"
	includePlg(); //sin parametros asume que la seccion es "header"
```

--> Add to beginning of **Server Events/Global/All Pages/Page_Loading**

```sh
	includePlg("loading");
```

--> Add to function body of **Server Events/Global/All Pages/Page_Rendering**

```sh
	includePlg("rendering");
```

--> Add to function body of **Server Events/Global/All Pages/Page_Unloaded**

```sh
	includePlg("unloaded");
```

--> Add to beginning of **Client Scripts/Global/Pages with header|footer/Client Script**

```sh
<?php
	includePlg("client_script");
?>
```

--> Add to beginning of **Client Scripts/Global/Pages with header|footer/StartUp Script**

```sh
<?php
	includePlg("footer");
?>
```

### Use

#### Sample

--> To enable "autosizetextarea" plugin for any **_Edit Page_**,
add to beginning of **Server Events/Table-Specific/Edit Page/Page_Load** section of page

```sh
	addPlg("plg_autosizetextarea");
```
