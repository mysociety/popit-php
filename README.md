#popit-php #

PHP SDK to connect to the PopIt API. You can *create*, *read*, *update* and *delete* any items from PopIt using the SDK.

## How do I use this when I want to... ##

First, you'll need to import the PopIt class and instantiate it

	require_once("popit.php");

    $popit = new PopIt(array(
        'instanceName' => [Instance name],
        'user' => [User Name],
        'password' => [Password],
        'hostName' => [Host name],
        'version' => [API Version],
    ));

* `instanceName` Name of the instance you created. There can be more than one for one installation.
* `hostName` The Hostname of the PopIt server.
* `version` The version of the PopIt API. Since there may be changes in the way you access the data in PopIt you want to have a stable API version. We recommend that you use the latest version, if possible.
* `user` Your username. You will not be able to add, update or delete anything if you haven't provided your username and password.
* `password` The password for the user.

### …create something? ###

The SDK lets you easily create a new item by name. This can be a `person`, `organisation` or `position`. There may be other options that you can find in the [PopIt API documentation]().

    $person = $popit->add("person", array('name' => 'Test User'));
    print_r($person);

	// get the id of the newly created item
    $id = $person['_id'];


### …read something ###

If you want to get a single item from PopIt, use get([entity name], [id])

	# you need a valid ID for example from the create process.
    $result = $popit->get("person", "4ffdfbcba90a340d49004796");
    print_r($result);

To get all Items from a kind, use get([entity name]).

    $result = $popit->get("person");
    print_r($result);

### …update something? ###

To update data of an item, use update([entity name], [id], [updated date]).

    $result = $popit->update("person", "4fe8b18dd4bd081b6b000204", array('name' => 'New name'));
    print_r(result);

### …delete something? ###

To delete an item, use delete([entity name], [id]).

    $popit->delete("person", "4ffdfbcba90a340d49004796");

### …get an error? ###

Check the exception message for the error details and trace.

## Requirements ##

* PHP CURL Extension
* PHP JSON Extension

### Credits ###

Much of this documentation has been carried over from https://github.com/domoritz/popit-python/blob/master/README.md