<?php
namespace Spray\Serializer\TestAssets;

use Spray\Serializer\AbstractObjectSerializer;

class InheritedSubjectSerializer extends AbstractObjectSerializer
{
    public function __construct($parents = null)
    {
        $self = $this;
        parent::__construct(
            function($subject, array &$data) use ($self) {
                $data['foobar'] = $subject->foobar;
            },
            function($subject, array &$data) use ($self) {
                $subject->foobar = $data['foobar'];
            },
            'Spray\Serializer\TestAssets\InheritedSubject',
            $parents
        );
    }
}
