<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Job;
use App\Entity\Auditor;
use DateTime;

class JobController extends AbstractController
{
    /**
     * @Route("/api/jobs", name="api_jobs", methods={"GET"})
     * Gets jobs depending on auditors timezone
     */
    public function index(EntityManagerInterface $entityManager): JsonResponse
    {
        //here we are getting a random user, since authentication is missing in this test task,
        //which will be used for the timezone, and also for the ID to assign to the job
        $auditors = $entityManager->getRepository(Auditor::class)->findAll();

        if (!$auditors) {
            return $this->json(['message' => 'No auditors found!']);
        }

        //picks randomly
        $auditor = $auditors[array_rand($auditors)];
        $timezone = $auditor->getLocation()->getTimezone();

        $query = $entityManager->getRepository(Job::class)
            ->createQueryBuilder('jobs')
            ->where('jobs.scheduled_date > :startDate AND jobs.auditor IS NULL')
            ->setParameter('startDate', (new DateTime())->setTimezone(new \DateTimeZone($timezone)))
            ->getQuery();

        $jobs = $query->getResult();

        return $this->json([
            'user_id' => $auditor->getId(),
            'jobs' => $jobs
        ]);
    }

    /**
     * @Route("/api/job/{id}/assign", name="job_assign", methods={"POST"})
     */
    public function assign(EntityManagerInterface $entityManager, ValidatorInterface $validator, Request $request, Job $job): JsonResponse
    {
        if (!$job) {
            return $this->json(['message' => 'Job is not found!'], 400);
        }

        if ($job->getAuditorId()) {
            return $this->json(['message' => 'Job is already assigned!'], 400);
        }

        $data = $request->getPayload()->all();

        //validate request
        $constraints = new Assert\Collection([
            'user_id' => [
                new Assert\NotBlank()
            ],
            'estimated_date' => [
                new Assert\NotBlank(),
            ],
        ]);

        $errors = $validator->validate($data, $constraints);
        if ($errors->count()) {
            return $this->json(['message' => $errors[0]->getMessage()], 400);
        }

        $auditor = $entityManager->getRepository(Auditor::class)->find($data['user_id']);

        if (!$auditor) {
            return $this->json(['message' => 'Auditor is not found!'], 400);
        }

        //update job
        $job->setAuditorId($auditor);
        $job->setEstimatedDate(\DateTime::createFromFormat('Y-m-d H:i', $data['estimated_date']));

        $entityManager->persist($job);
        $entityManager->flush();

        return $this->json(['message' => 'Job assigned successfully']);
    }

    /**
     * @Route("/api/job/{id}/complete", name="job_complete", methods={"POST"})
     */
    public function complete(EntityManagerInterface $entityManager, ValidatorInterface $validator, Request $request, Job $job): JsonResponse
    {
        if (!$job) {
            return $this->json(['message' => 'Job is not found!'], 400);
        }

        $data = $request->getPayload()->all();

        //validate request
        $constraints = new Assert\Collection([
            'user_id' => [
                new Assert\NotBlank()
            ],
            'assessment' => [
                new Assert\NotBlank()
            ]
        ]);

        $errors = $validator->validate($data, $constraints);
        if ($errors->count()) {
            return $this->json(['message' => $errors[0]->getMessage()], 400);
        }

        //update job
        $job->setCompletedDate((new DateTime())->setTimezone(new \DateTimeZone('UTC')));
        $job->setAssessment($data['assessment']);

        $entityManager->persist($job);
        $entityManager->flush();

        return $this->json(['message' => 'Job completed successfully']);
    }
}
