<?php

namespace App\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Validator\ConstraintViolationList;
use App\Entity\User;

class UserEntityTest extends KernelTestCase
{
        private const EMAIL_CONSTRAINT_MESSAGE = "L'email {{ value }} n'est pas valide.";
        private const NOT_BLANK_MESSAGE = "Veuillez saisir une valeur";
        private const INVALID_EMAIL_VALUE = 'jerome.lorentz@gmail';
        private const PASSWORD_REGEX_CONSTRAINT_MESSAGE ="Le mot de passe doit comporter plus de caractères";
        private const VALID_EMAIL_VALUE = "jerome.lorentz@gmail.com";
        private const VALID_PASSWORD_VALUE = "mot-de-passe978";

        private ValidatorInterface $validator;

        protected function setUp(): void
        {
                $kernel = self::bootKernel();

                $this->validator = $kernel->getContainer()->get('validator');
        }

        public function testValidUserEntity()
        {
                $user = new User();
                $user->setEmail(self::VALID_EMAIL_VALUE);
                $user->setPassword(self::VALID_PASSWORD_VALUE);

                $this->getValidatorErrors($user, 0);
           
        }
        private function getValidatorErrors(User $user, int $numberOfExpectedErrors):ConstraintViolationList
        {
                $errors = $this->validator->validate($user);
                $this->assertCount($numberOfExpectedErrors, $errors);

                return $errors;
        }


        


    
}
