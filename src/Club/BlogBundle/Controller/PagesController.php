<?php

namespace Club\BlogBundle\Controller;


use Club\BlogBundle\Entity\Messages;
use Club\BlogBundle\Form\ContactType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class PagesController extends BaseController
{
    /**
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\Form\Exception\AlreadySubmittedException
     * @Route("/contact", name="blog_pages_contact")
     */
    public function contactAction(Request $request)
    {
        $form = $this->createForm(ContactType::class);
        $Contact = new Messages();

        if (null !== $this->getUser()) {
            $User = $this->getUser();
            $form->setData(
                [
                    'name' => $User->getUsername(),
                    'email' => $User->getEmail()
                ]
            );
        }
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $sendToEmail = $this->container->getParameter('contact_send_to');
                $sendFromEmail = $this->container->getParameter('mailer_user');
                $emailBody = $this->renderView(
                    'ClubBlogBundle:Email:contact.html.twig',
                    [
                        'name' => $form->get('name')->getData(),
                        'email' => $form->get('email')->getData(),
                        'message' => $form->get('message')->getData(),
                    ]
                );
                $message = \Swift_Message::newInstance()
                    ->setSubject('[Draco Kowala] Kontakt')
                    ->setTo($sendToEmail)
                    ->setFrom($sendFromEmail, 'Draco Kowala')
                    ->setBody($emailBody, 'text/html');

                $this->get('mailer')->send($message);
                $this->get('session')->getFlashBag()->add('success', 'Twoja wiadomość została wysłana!');


                $Contact->setName($form->get('name')->getData())
                    ->setEmail($form->get('email')->getData())
                    ->setMessage($form->get('message')->getData());

                $em = $this->getDoctrine()->getManager();
                $em->persist($Contact);
                $em->flush();

                return $this->redirect($this->generateUrl('blog_pages_contact'));
            }
        }
        return $this->render(
            'ClubBlogBundle:Pages:contact.html.twig',
            array(
                'form' => $form->createView(),
                'pageTitile'    =>  'Kontakt'
            )
        );

    }

    /**
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/galeria", name="blog_pages_gallery")
     */
    public function galleryAction()
    {
        return $this->render('ClubBlogBundle:Pages:gallery.html.twig');
    }
}
