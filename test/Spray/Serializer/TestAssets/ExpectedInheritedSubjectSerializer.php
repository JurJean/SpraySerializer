<?php
namespace Spray\Serializer\TestAssets;

use Spray\Serializer\AbstractObjectSerializer;
use Spray\Serializer\SerializerInterface;

class InheritedSubjectSerializer extends AbstractObjectSerializer
{
    public function __construct()
    {
        parent::__construct(
            function($subject, array &$data, SerializerInterface $serializer) {
                $data['foobar'] = $serializer->serialize($subject->foobar);
                $data['barbaz'] = $serializer->serialize($subject->barbaz);
            },
            function($subject, array &$data, SerializerInterface $serializer) {
                $subject->foobar = $serializer->deserialize('Spray\Serializer\TestAssets\Subject', $data['foobar']);
                $subject->barbaz = $serializer->deserialize('DateTime', $data['barbaz']);
            },
            'Spray\Serializer\TestAssets\InheritedSubject'
        );
    }
}
