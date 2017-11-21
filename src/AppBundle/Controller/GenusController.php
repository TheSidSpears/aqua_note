<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Genus;
use AppBundle\Service\MarkdownTransformer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class GenusController extends Controller
{
    /**
     * @Route("/genus")
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $genuses = $em->getRepository('AppBundle:Genus')
            ->findAllPublishedOrderedByRecentlyActive();

        return $this->render('genus/list.html.twig', [
            'genuses' => $genuses
        ]);
    }

    /**
     * @Route("/genus/{genusName}", name="genus_show")
     */
    public function showAction($genusName, MarkdownTransformer $markdownTransformer)
    {
        $em = $this->getDoctrine()->getManager();

        $genus = $em->getRepository('AppBundle:Genus')
            ->findOneBy(['name' => $genusName]);

        if (!$genus) {
            throw $this->createNotFoundException('genus not found');
        }

        $funFact = $markdownTransformer->parse($genus->getFunFact());

        $this->get('logger')
            ->info('Showing genus: ' . $genusName);

        $recentNotes = $em->getRepository('AppBundle:GenusNote')
            ->findAllRecentNotesForGenus($genus);

        return $this->render('genus/show.html.twig', array(
            'genus' => $genus,
            'recentNoteCount' => count($recentNotes),
            'funFact' => $funFact
        ));
    }

    /**
     * @Route("/genus/{name}/notes", name="genus_show_notes")
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
}
