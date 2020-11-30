
# component
A pseudo-port of [exponent-ts](https://github.com/RepComm/exponent-ts) for PHP

Example usage
```php

//include the lib
include_once("component.php");

function test_component_php () {
  //Create a container for our content
  $content = new Component();

  $content->make("div")->
  textContent("Hello World")->

  //Styling
  styleItem("position", "absolute")->
  styleItem("top", "25%")->
  styleItem("left", "25%")->
  styleItem("width", "50vw")->
  styleItem("height", "50vh")->
  styleItem("background-color", "black")->
  styleItem("color", "white");

  //Iterate from 0 to 9

  //Declaring variable outside of the loop should save us some performance
  $child = 0;

  for ($i=0; $i < 10; $i++) {
    //Create a line break
    $child = new Component();
    $child->make("br")->

    //attach to the content as a child
    mount($content);

    //Create text span
    $child = new Component();
    $child->make("span")->

    //set the text content to "span " + iteration number
    textContent("span " . sprintf("%d", $i) )->

    //attach to the content as a child
    mount($content);
  }

  //walk the hierarchy and echo the HTML generated
  writeComponent($content);
}

test_component_php();
```
