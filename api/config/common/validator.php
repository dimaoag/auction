<?php

declare(strict_types=1);

use Doctrine\Common\Annotations\AnnotationRegistry;
use Symfony\Component\Validator\Validation;
use Psr\Container\ContainerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

return [
    ValidatorInterface::class => function (ContainerInterface $container): ValidatorInterface {
        /** @psalm-suppress DeprecatedMethod */
        AnnotationRegistry::registerLoader('class_exists');

        $translator = $container->get(TranslatorInterface::class);


        return Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->setTranslator($translator)
            ->setTranslationDomain('validators')
            ->getValidator();
    },
];
