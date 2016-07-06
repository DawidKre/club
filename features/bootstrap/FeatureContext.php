<?php

//namespace features\BlogBundle;

use Behat\Behat\Context\SnippetAcceptingContext;

use Behat\Mink\Element\DocumentElement;
use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Symfony2Extension\Context\KernelDictionary;
use Club\BlogBundle\Entity\Category;
use Club\BlogBundle\Entity\Comment;
use Club\BlogBundle\Entity\Post;
use Club\UserBundle\Entity\User;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;



require_once __DIR__ . '/../../vendor/phpunit/phpunit/src/Framework/Assert/Functions.php';

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends RawMinkContext implements SnippetAcceptingContext
{
    use KernelDictionary;


    /**
     * FeatureContext constructor.
     *
     */
    public function __construct()
    {

    }
    /**
     * @return DocumentElement
     */
    private function getPage()
    {
        $this->getSession()->getPage();
    }


    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->getContainer()->get('doctrine')->getManager();
    }


    public function createArticle($count = 1, $author = null, $password = 'admin', $role = null, $category = null, $title = null, $content = '', $match = 0)
    {
        $em = $this->getEntityManager();
        if ($author == null) {
            $user = $this->createUser('admin' . rand(1, 100000000), 'admin');
        } else {
            $user = $this->createUser($author, $password, $role = 'ROLE_ADMIN');
        }

        if ($category == null) {
            $newCategory = $this->createCategory('cat' . rand(1, 10000000));
        } else {
            $newCategory = $this->createCategory($category);
        }

        for ($i = 0; $i < $count; $i++) {
            $post = new Post();
            if ($title == null) {
                $post->setTitle('Article' . $i . rand(1, 999999));
            } else {
                if ($count != 1) {
                    $post->setTitle($title . ' ' . $i . rand(1, 999999));
                } else {
                    $post->setTitle($title);
                }
            }
            if ($content == '') {
                $post->setContent('Hercle, tus flavum!.Fatalis decors ducunt ad fraticinida.');
            } else {
                $post->setContent($content);
            }

            $post->setAuthor($user);
            $post->setIsMatch($match);
            $post->setCategory($newCategory);

            $em->persist($post);
        }
        $em->flush();
    }

    public function createCategory($category = null) 
    {
        $newCategory = new Category();
        $newCategory->setName($category);

        return $newCategory;
    }


    public function createUser($username, $plainPassword, $role = 'ROLE_ADMIN', $email = null)
    {
        $user = new User();
        if ($email == null) {
            $user->setEmail('John' . rand(0, 10000) . '@draco.com');
        } else {
            $user->setEmail($email);
        }
        $user->setUsername($username);
        $user->setPlainPassword($plainPassword);
        $user->setRoles(array($role));
        $user->setEnabled(true);

        $em = $this->getEntityManager();
        $em->persist($user);
        $em->flush();

        return $user;
    }

    // Not found Post entity
    public function createComment($post = null, $author = null, $commentBody = null, $email = null)
    {
        $Comment = new Comment();
        if ($author == null) {
            $user = 'user';
        } else {
            $user = $author->getUsername();
            $Comment->setAuthor($user);
        }
        if ($commentBody == null) {
            $commentBody = 'Bassus, mirabilis axonas una demitto de talis, placidus nutrix.Domesticus, castus onuss foris amor de bi-color, clemens visus.';
        }
        if ($email == null) {
            $email = 'email@com.pl';
        }
        $Comment->setPost($post);
        $Comment->setUser($user);
        $Comment->setComment($commentBody);
        $Comment->setEmail($email);

        $em = $this->getEntityManager();
        $em->persist($Comment);
        $em->flush();

        return $Comment;
    }

    public function createTeam($name = 'Draco Kowala')
    {
        $team = new \Club\GameBundle\Entity\Team();
        $team->setName($name);

        $em = $this->getEntityManager();
        $em->persist($team);
        $em->flush();

        return $team;
    }

    public function createPlayer($count = 1, $name = null, $team = null, $position = null)
    {

        if ($position == null) {
            $position = $this->createPosition('Napastnik' . rand(0, 1000000));
        }

        $em = $this->getEntityManager();

        for ($i = 0; $i < $count; $i++) {
            $player = new \Club\GameBundle\Entity\Player();
            if ($name == null) {
                $player->setName('John' . rand(0, 10000));
            } else {
                if ($count == 1) {
                    $player->setName($name);
                } else {
                    $player->setName($name . rand(0, 10000));
                }
            }
            $player->setTeam($team);
            $player->setPosition($position);

            $em->persist($player);
            $em->flush();
        }

    }

    public function createPositions($count = 1, $name = null)
    {
        $em = $this->getEntityManager();
        for ($i = 0; $i < $count; $i++) {
            $position = new \Club\GameBundle\Entity\Position();
            if ($name == null) {
                $position->setName('Position' . rand(0, 10000));
            } else {
                if ($count == 1) {
                    $position->setName($name);
                } else {
                    $position->setName($name . rand(0, 10000));
                }
            }
            $position->setPositionOrder($i);

            $em->persist($position);
            $em->flush();
        }
    }

    public function createPosition($name)
    {
        $position = new \Club\GameBundle\Entity\Position();
        $position->setName($name);

        $em = $this->getEntityManager();
        $em->persist($position);
        $em->flush();

        return $position;
    }
    
    
    public function clearData()
    {
        $purger = new ORMPurger($this->getContainer()->get('doctrine')->getManager());
        $purger->purge();

    }
    /**
     * Saving a screenshot
     *
     * @When I save a screenshot to :filename
     */
    public function iSaveAScreenshotIn($filename)
    {
        sleep(1);
        $this->saveScreenshot($filename, __DIR__ . '/../..');
    }

}
