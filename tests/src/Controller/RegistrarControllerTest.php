<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;

final class RegistrarControllerTest extends WebTestCase
{
    /** @var \Symfony\Bundle\FrameworkBundle\KernelBrowser */
    private $client;

    /** @var EntityManagerInterface */
    private $em;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        $this->em = static::getContainer()->get('doctrine')->getManager();
        $user = $this->em->getRepository(User::class)->findOneBy(['email' => 'johndoe@gmail.com']);
        if ($user) {
            $this->em->remove($user);
            $this->em->flush();
        }
    }

    public function testGetRoute(): void
    {
        $this->client->request('GET', '/registrar');
        $this->assertResponseIsSuccessful();
    }

    public function testCreateNewUser(): void
    {
        $crawler = $this->client->request('GET', '/registrar');
        $form = $crawler->filter('form[name="registrar"]')->form();
        $form['registrar[email]'] = 'johndoe@gmail.com';
        $form['registrar[password]'] = '123';
        $this->client->submit($form);
        $user = $this->em->getRepository(User::class)->findOneBy(['email' => 'johndoe@gmail.com']);
        $this->assertNotNull($user, 'User should be created');
    }
}
?>
