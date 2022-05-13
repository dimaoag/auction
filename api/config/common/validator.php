<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

return [
    ValidatorInterface::class => static function (ContainerInterface $container): ValidatorInterface {
        $translator = $container->get(TranslatorInterface::class);

        return Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->setTranslator($translator)
            ->setTranslationDomain('validators')
            ->getValidator();
    },
];
