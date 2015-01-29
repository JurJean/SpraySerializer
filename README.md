SpraySerializer
===============

Allows easy serialization and deserialization of php objects.

Internals
---------

Serialization is performed by attaching to a specific class scope using the Closure::bind method.

To keep things fast, reflection is only used while generating the actual serialization code.

How to use
----------

Let's start with a class to serialize. Note that the annotations hint the serializer, and that they're required for deserializing objects.

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

Then we'll initialize the serializer.

    $serializer = new Serializer(
        new SerializerLocator(
            new SerializerRegistry(),
            new ObjectSerializerBuilder(
                new ReflectionRegistry()
            ),
            new ArrayCache()
        )
    );

Now we can serialize almost any object to an array and back to an object.

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
