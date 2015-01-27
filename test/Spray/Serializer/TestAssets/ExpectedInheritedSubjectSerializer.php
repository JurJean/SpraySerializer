<?php
namespace Spray\Serializer\TestAssets;

use Spray\Serializer\AbstractObjectSerializer;

class InheritedSubjectSerializer extends AbstractObjectSerializer
{
    public function __construct($parents = null)
    {
        $self = $this;
        parent::__construct(
            function($subject, array &$data, SerializerInterface $parent = null) use ($self) {
                $data['foobar'] = $parent->serialize($subject->foobar);
            },
            function($subject, array &$data, SerializerInterface $parent = null) use ($self) {
                $subject->foobar = $parent->deserialize('Spray\Serializer\TestAssets\Subject', $data['foobar']);
            },
            'Spray\Serializer\TestAssets\InheritedSubject',
            $parents
        );
    }
}
