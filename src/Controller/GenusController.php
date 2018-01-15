<?php

namespace App\Controller;

use App\Entity\Genus;
use App\Entity\GenusNote;
use App\Entity\GenusScientist;
use App\Service\MarkdownTransformer;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GenusController extends Controller
{

    /**
     * @Route("/genus/new")
     */
    public function newAction()
    {
        $em = $this->getDoctrine()->getManager();

        $subFamily = $em->getRepository('App:SubFamily')
            ->findAny();

        $genus = new Genus();
        $genus->setName('Octopus' . rand(1, 10000));
        $genus->setSubFamily($subFamily);
        $genus->setSpeciesCount(rand(100, 99999));
        $genus->setFirstDiscoveredAt(new \DateTime('50 years'));

        $genusNote = new GenusNote();
        $genusNote->setUsername('AquaWeaver');
        $genusNote->setUserAvatarFilename('ryan.jpeg');
        $genusNote->setNote('I counted 8 legs... as they wrapped around me');
        $genusNote->setCreatedAt(new \DateTime('-1 month'));
        $genusNote->setGenus($genus);

        $user = $em->getRepository('App:User')
            ->findOneBy(['email' => 'aquanaut1@example.org']);

        $genusScientist = new GenusScientist();
        $genusScientist->setGenus($genus);
        $genusScientist->setUser($user);
        $genusScientist->setYearsStudied(10);
        $em->persist($genusScientist);

        $em->persist($genus);
        $em->persist($genusNote);
        $em->flush();

        return new Response(sprintf(
            '<html><body>Genus created! <a href="%s">%s</a></body></html>',
            $this->generateUrl('genus_show', ['slug' => $genus->getSlug()]),
            $genus->getName()
        ));
    }

    /**
     * @Route("/genus")
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $genuses = $em->getRepository('App:Genus')
            ->findAllPublishedOrderedByRecentlyActive();

        return $this->render('genus/list.html.twig', [
            'genuses' => $genuses
        ]);
    }

    /**
     * @Route("/genus/feed", name="genus_feed")
     */
    public function feedAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->query->get('id');
        $genus = $em->getRepository('App:Genus')->find($id);

        $menu = ['shrimp', 'clams', 'lobsters', 'dolphin'];
        $meal = $menu[random_int(0, 3)];

        $this->addFlash('info', $genus->feed([$meal]));

        return $this->redirectToRoute('easyadmin', [
            'action' => 'show',
            'entity' => $request->query->get('entity'),
            'id' => $id
        ]);
    }

    /**
     * @Route("/genus/{slug}", name="genus_show")
     */
    public function showAction(Genus $genus, MarkdownTransformer $markdownTransformer, LoggerInterface $logger)
    {
        $em = $this->getDoctrine()->getManager();

        $funFact = $markdownTransformer->parse($genus->getFunFact());

        $logger->info('Showing genus: ' . $genus->getName());

        $recentNotes = $em->getRepository('App:GenusNote')
            ->findAllRecentNotesForGenus($genus);

        return $this->render('genus/show.html.twig', array(
            'genus' => $genus,
            'recentNoteCount' => count($recentNotes),
            'funFact' => $funFact
        ));
    }

    /**
     * @Route("/genus/{slug}/notes", name="genus_show_notes")
     * @Method("GET")
     */
    public function getNotesAction(Genus $genus)
    {
        $notes = [];

        foreach ($genus->getNotes() as $note) {
            $notes[] = [
                'id' => $note->getId(),
                'username' => $note->getUsername(),
                'avatarUri' => '/images/' . $note->getUserAvatarFilename(),
                'note' => $note->getNote(),
                'date' => $note->getCreatedAt()->format('M d, Y')
            ];
        }

        $data = [
            'notes' => $notes
        ];

        return new JsonResponse($data);
    }

    /**
     * @Route("/genus/{genusId}/scientists/{userId}", name="genus_scientists_remove")
     * @Method("DELETE")
     */
    public function removeGenusScientistAction($genusId, $userId)
    {
        $em = $this->getDoctrine()->getManager();

        $genusScientist = $em->getRepository('App:GenusScientist')
            ->findOneBy([
                'user' => $userId,
                'genus' => $genusId
            ]);

        if (!$genusScientist) {
            throw $this->createNotFoundException('scientist not found');
        }

        $em->remove($genusScientist);
        $em->flush();

        return new Response(null, 204);
    }
}
