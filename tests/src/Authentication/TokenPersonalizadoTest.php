<?php

namespace App\Tests\Authentication;

use App\Authentication\TokenPersonalizado;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

final class TokenPersonalizadoTest extends KernelTestCase
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var JWTTokenManagerInterface */
    private $jwtManager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->em = static::getContainer()->get('doctrine')->getManager();
        $this->jwtManager = static::getContainer()->get(JWTTokenManagerInterface::class);
        
        $user1 = $this->em->getRepository(User::class)->findOneBy(['email' => 'johndoe1@test.com']);
        $user2 = $this->em->getRepository(User::class)->findOneBy(['email' => 'johndoe2@test.com']);
        
        if (!$user1) {
            $user = new User();
            $user->setEmail('johndoe1@test.com');
            $user->setPassword('123');
            $this->em->persist($user);
            $this->em->flush();
        };
        if (!$user2) {
            $user = new User();
            $user->setEmail('johndoe2@test.com');
            $user->setPassword('123');
            $this->em->persist($user);
            $this->em->flush();
        }
    }

    public function testNuevoToken(): void
    {
        $tokenPersonalizado = new TokenPersonalizado($this->jwtManager);
        
        $user1 = $this->em->getRepository(User::class)->findOneBy(['email' => 'johndoe1@test.com']);
        $user2 = $this->em->getRepository(User::class)->findOneBy(['email' => 'johndoe2@test.com']);

        $jwt = $tokenPersonalizado->nuevo_token($user1, $user2);
        $this->assertIsString($jwt);

        $data = $this->jwtManager->parse($jwt);
        $this->assertTrue(array_key_exists('iat', $data));
        $this->assertTrue(array_key_exists('exp', $data));
        $this->assertTrue(array_key_exists('roles', $data));
        $this->assertTrue(array_key_exists('id_cliente', $data));
        $this->assertTrue(array_key_exists('username', $data));
        $this->assertEquals($data['username'], $user2->getEmail());
    }

    public function testDecodificarToken(): void
    {
        $tokenPersonalizado = new TokenPersonalizado($this->jwtManager);
        $user1 = $this->em->getRepository(User::class)->findOneBy(['email' => 'johndoe1@test.com']);
        $user2 = $this->em->getRepository(User::class)->findOneBy(['email' => 'johndoe2@test.com']);
        $jwt = $tokenPersonalizado->nuevo_token($user1, $user2);
        $decoded_jwt = $tokenPersonalizado->decodificar_token($jwt);

        $this->assertTrue(array_key_exists('iat', $decoded_jwt));
        $this->assertTrue(array_key_exists('exp', $decoded_jwt));
        $this->assertTrue(array_key_exists('roles', $decoded_jwt));
        $this->assertTrue(array_key_exists('id_cliente', $decoded_jwt));
        $this->assertTrue(array_key_exists('username', $decoded_jwt));
    }

    public function testValidarToken(): void
    {
        $tokenPersonalizado = new TokenPersonalizado($this->jwtManager);
        $user1 = $this->em->getRepository(User::class)->findOneBy(['email' => 'johndoe1@test.com']);
        $user2 = $this->em->getRepository(User::class)->findOneBy(['email' => 'johndoe2@test.com']);
        $jwt = $tokenPersonalizado->nuevo_token($user1, $user2);
        
        $token_valido = $tokenPersonalizado->validar_token($jwt);
        $this->assertTrue($token_valido, true);
    }
}
?>
