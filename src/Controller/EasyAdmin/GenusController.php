<?php

namespace App\Controller\EasyAdmin;

use App\Entity\Genus;
use App\Service\CsvExporter;

class GenusController extends AdminController
{
    private $csvExporter;

    public function __construct(CsvExporter $csvExporter)
    {
        $this->csvExporter = $csvExporter;
    }

    public function changePublishedStatusAction()
    {
        $id = $this->request->query->get('id');
        $entity = $this->em->getRepository('App:Genus')->find($id);

        $entity->setIsPublished(!$entity->getIsPublished());

        $this->em->flush();

        $this->addFlash('success', sprintf('Genus %spublished!', $entity->getIsPublished() ? '' : 'un'));

        return $this->redirectToRoute('easyadmin', [
            'action' => 'show',
            'entity' => $this->request->query->get('entity'),
            'id' => $id,
        ]);
    }

    public function exportAction()
    {
        $sortDirection = $this->request->query->get('sortDirection');
        if (empty($sortDirection) || !in_array(strtoupper($sortDirection), ['ASC', 'DESC'])) {
            $sortDirection = 'DESC';
        }

        $queryBuilder = $this->createListQueryBuilder(
            $this->entity['class'],
            $sortDirection,
            $this->request->query->get('sortField'),
            $this->entity['list']['dql_filter']
        );

        return $this->csvExporter->getResponseFromQueryBuilder(
            $queryBuilder,
            Genus::class,
            'genuses.csv'
        );
    }
}
