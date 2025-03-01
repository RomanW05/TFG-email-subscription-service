<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;

final class LoginTest extends WebTestCase
{
    /** @var \Symfony\Bundle\FrameworkBundle\KernelBrowser */
    private $client;

    /** @var EntityManagerInterface */
    private $em;

    protected function setUp(): void
    {
        parent::setUp();

        // 1) Create your client only once per test method
        $this->client = static::createClient();

        // 2) Grab the EntityManager
        $this->em = static::getContainer()->get('doctrine')->getManager();

        $user = $this->em->getRepository(User::class)->findOneBy(['email' => 'johndoe']);
        if (!$user) {
            $user = new User();
            $user->setEmail('johndoe');
            $user->setPassword('secret123');
            $this->em->persist($user);
            $this->em->flush();
        }
    }

    public function testCreateNewUser(): void
    {
        $user = $this->em->getRepository(User::class)->findOneBy(['email' => 'johndoe']);
        if ($user) {
            $this->em->remove($user);
            $this->em->flush();
        }

        $this->client->request(
            'POST', 
            '/login/create_new_user', 
            [], // parameters
            [], // files
            ['CONTENT_TYPE' => 'application/json'], // server headers
            json_encode([
                'username' => 'johndoe',
                'password' => 'secret123'
            ]) // content
        );

        $this->assertResponseIsSuccessful();
        $user = $this->em->getRepository(User::class)->findOneBy(['email' => 'johndoe']);
        $this->assertNotNull($user, 'User should be created');
    }

    public function testCustomToken(): void
    {
        $user = $this->em->getRepository(User::class)->findOneBy(['email' => 'johndoe']);
        if (!$user) {
            $user = new User();
            $user->setEmail('johndoe');
            $user->setPassword('secret123');
            $this->em->persist($user);
            $this->em->flush();
        }

        $this->client->request('GET', '/login/custom_token');
        $this->assertResponseIsSuccessful();
    }
}
?>