
# component
A pseudo-port of [exponent-ts](https://github.com/RepComm/exponent-ts) for PHP

Example usage
```php

//include the lib
include_once("component.php");

//Create a new componet
$mydiv = new Component();

$mydiv->
make("div")->                               //make it a <DIV>
textContent ("Hello World Div")->           //set the text
styleItem   ("background-color", "white")-> //style
styleItem   ("color"           , "black");  //style

//Make a line break
$br = new Component();
$br->
make("br")->
mount($mydiv);

//Create a new component
$myspan = new Component();

$myspan->
make("span")->           //make it a <SPAN>
textContent("My span")-> //set the text
mount($mydiv);           //attach to div parent

//output to the page
echo $mydiv->buildFullTag();
```
