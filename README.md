SpraySerializer
===============

Fast and easy serialization and deserialization of php objects.

[![Build Status](https://secure.travis-ci.org/JurJean/SpraySerializer.png?branch=master)](http://travis-ci.org/JurJean/SpraySerializer)

Internals
---------

Serialization is performed by attaching to a specific class scope using the Closure::bind method. To keep things fast, reflection is only used while generating the serialization code.

How to use
----------

Let's start with a class to serialize. Note that the annotations hint the serializer, and that they're required for deserializing objects.

```php
/**
 * Person
 */
class Person
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var Address
     */
    private $address;

    public function __construct($name, Address $address)
    {
        $this->name = (string) $name;
        $this->address = $address;
    }
}

/**
 * Address
 */
class Address
{
    /**
     * @var string
     */
    private $street;

    public function __construct($street)
    {
        $this->street = (string) $street;
    }
}
```

Then we'll initialize the serializer.

```php
$serializer = new Serializer();
$serializer->attach(
    new ObjectListener(
        new SerializerLocator(
            new SerializerRegistry(),
            new ObjectSerializerGenerator(
                new AnnotationBackedPropertyInfo()
            ),
            new ArrayCache('Serializer')
        )
    )
);
```

Now we can serialize almost any object to an array and back to an object.

```php
$data = $serializer->serialize(new Person('Name', new Address('Street')));
var_dump($data);
// array(2) {
//   'name' =>
//   string(4) "Name"
//   'address' =>
//   array(1) {
//     'street' =>
//     string(6) "Street"
//   }
// }


$object = $serializer->deserialize('Person', $data);
var_dump($object);
// class Person#8 (2) {
//   private $name =>
//   string(4) "Name"
//   private $address =>
//   class Address#18 (1) {
//     private $street =>
//     string(6) "Street"
//   }
// }
```

[Supported annotations](examples/simple.php)
--------------------------------------------

As the example above shows, the serializer uses default docblock annotations to determine the serialization strategy. The following annotations are supported:

```php
class SerializeMe
{
    /**
     * @var string
     */
    private $string;

    /**
     * @var int
     */
    private $int;

    /**
     * @var integer
     */
    private $integer;

    /**
     * @var bool
     */
    private $bool;

    /**
     * @var boolean
     */
    private $boolean;

    /**
     * @var float
     */
    private $float;

    /**
     * @var double
     */
    private $double;

    /**
     * @var array
     */
    private $array;

    /**
     * @var Object
     */
    private $object;

    /**
     * @var string[]
     */
    private $stringArray;

    /**
     * @var Object[]
     */
    private $objectArray;

    /**
     * @var array<string>
     */
    private $stringArrayJavaStyle;

    /**
     * @var array<Object>
     */
    private $objectArrayJavaStyle;
}
```

[Almost any object](examples/custom.php)
-----------------

There're some limitations to the implemented serialization method. For instance,
deserializing a DateTime(Immutable) object is not possible. For this reason,
specialized serializers are added. You'll need to add these to the
SerializerRegistry in your application bootstrap like so:

```php
$registry = new SerializerRegistry();
$registry->add(new DateTimeSerializer());
$registry->add(new DateTimeImmutableSerializer());
$registry->add(new StdClassSerializer());

$serializer = new Serializer();
$serializer->attach(
    new ObjectListener(
        new SerializerLocator(
            $registry,
            new ObjectSerializerGenerator(
                new AnnotationBackedPropertyInfo()
            ),
            new ArrayCache('Serializer')
        )
    )
);
```

[Inheritance support](examples/inheritance.php)
-----------------------------------------------

In order to support object inheritance (de)serialization, just the annotations is not enough. The _ObjectTypeListener_ is required to enable this functionality:

```php
$serializer = new Serializer();
$serializer->attach(
    new ObjectTypeListener()
);
$serializer->attach(
    new ObjectListener(
        new SerializerLocator(
            new SerializerRegistry(),
            new ObjectSerializerGenerator(
                new AnnotationBackedPropertyInfo()
            ),
            new ArrayCache('Serializer')
        )
    )
);
```

___Note:__ Enabling this feature results in your data populated with ` '__type' => 'ClassName' `._

[Encryption support](examples/encryption.php)
--------------------------------------------

When your application requires encryption you'll have to attach the _EncryptionListener_:

```php
$blockCipher = BlockCipher::factory('mcrypt', ['algo' => 'aes']);
$blockCipher->setKey('5eDCZRmyX8s7nbgV9f6pVrmRISdc5t8L');

$serializer = new Serializer();
$serializer->attach(
    new EncryptionListener(
        new EncryptorLocator(
            new EncryptorGenerator(new AnnotationBackedPropertyInfo()),
            new ArrayCache('Encryptor')
        ),
        $blockCipher
    )
);
$serializer->attach(
    new ObjectListener(
        new SerializerLocator(
            new SerializerRegistry(),
            new ObjectSerializerGenerator(
                new AnnotationBackedPropertyInfo()
            ),
            new ArrayCache('Serializer')
        )
    )
);
```

[Caching methods](examples/cache.php)
-------------------------------------

The library provides two methods of caching: array and file. The array cache is
primarily useful for testing/development purposes. For production however, you're
better off using the FileCache.

The file cache actually writes the generated serialization code to plain php
files for later use (and therefore automatically cached in op-code cache).

Below is how you'd bootstrap the file cache for the serializer:

```php
use Symfony\Component\Filesystem\Filesystem;

$serializer = new Serializer();
$serializer->attach(
    new ObjectListener(
        new SerializerLocator(
            new SerializerRegistry(),
            new ObjectSerializerGenerator(
                new AnnotationBackedPropertyInfo()
            ),
            new FileCache(new Filesystem(), '/path/to/cache/directory', 'Serializer')
        )
    )
);
```
