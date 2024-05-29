<?php

namespace App\Controller;

use App\Entity\Module;
use App\Entity\Sensor;
use App\Enum\SensorType;
use App\Form\ModuleType;
use App\Repository\ModuleRepository;
use App\Service\SerializationContextBuilder;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Serializer\SerializerInterface;

class AppController extends AbstractController
{
    /**
     * Returns the view of s list (JSON format).
     *
     * @param SerializerInterface $serializer
     * @param SerializationContextBuilder $serializationContextBuilder
     * @param ModuleRepository $moduleRepo
     * @return Response
     */
    #[Route('/', name: 'app')]
    public function index(SerializerInterface $serializer, SerializationContextBuilder $serializationContextBuilder,  ModuleRepository $moduleRepo): Response
    {
        $data = $serializer->serialize($moduleRepo->findAll(), 'json', $serializationContextBuilder->buildContext());

        return $this->render('app/app.html.twig', ["data" => $data]);
    }

    /**
     * Create a `Module`.
     *
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/create', name: 'app_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $module = new Module();

        $form = $this->createForm(ModuleType::class, $module);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->processForm($form, $module, $entityManager);

            $this->addFlash('create_module_success', "Your module " . $module->getName() . " has been created successfully !");

            return $this->redirectToRoute('app');
        }

        return $this->render('app/module_form.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/edit/{id}', name: 'app_edit', requirements: ['id' => Requirement::UUID])]
    public function edit(Request $request, EntityManagerInterface $entityManager, string $id): Response
    {
        $module = $entityManager->getRepository(Module::class)->find($id);

        if (!$module) return $this->redirectToRoute('app');

        $form = $this->createForm(ModuleType::class, $module);

        $form->get('sensorTypes')->setData($module->getSensorsTypes());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->processForm($form, $module, $entityManager);

            $this->addFlash('edit_module_success', "Your module " . $module->getName() . " has been updated successfully !");

            return $this->redirectToRoute('app');
        }

        return $this->render('app/module_form.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_delete', requirements: ['id' => Requirement::UUID])]
    public function delete(EntityManagerInterface $entityManager, string $id): Response
    {
        $module = $entityManager->getRepository(Module::class)->find($id);

        if ($module) {

            $entityManager->remove($module);

            $entityManager->flush();

            $this->addFlash('delete', "Your module has been deleted successfully !");
        }

        return $this->redirectToRoute('app');
    }

    /**
     * Process `Module` form. 
     *
     * @param FormInterface $form
     * @param Module $module
     * @param EntityManager $entityManager
     * @return void
     */
    private function processForm(FormInterface $form, Module $module, EntityManager $entityManager): void
    {
        $sensorTypes = $form->get("sensorTypes")->getData();

        $this->setSensors($module, $sensorTypes, $entityManager);

        $entityManager->persist($module);
        $entityManager->flush();
    }

    /**
     * Process `Module` form. 
     *
     * @param Module $module
     * @param array[SensorType] $sensorTypes
     * @param EntityManager $entityManager
     * @return void
     */
    private function setSensors(Module $module, array $sensorTypes, EntityManager $entityManager): void
    {
        if (!$module->getId()) {
            $this->createSensors($module, $sensorTypes, $entityManager);
            $module->setCreatedAt(new \DateTimeImmutable());
            return;
        }

        $sensors = $module->getSensors()->toArray();

        for ($i = 0; $i < count($sensors); $i++) {

            if (in_array($sensors[$i]->getType(), $sensorTypes)) {
                $sensors[$i]->setName($sensors[$i]->getType()->value . "_" . $module->getName());
                $indexSensorType = array_search($sensors[$i]->getType(), $sensorTypes);
                unset($sensorTypes[$indexSensorType]);
            } else {
                $module->removeSensor($sensors[$i]);
                $entityManager->remove($sensors[$i]);
            }
        }

        $this->createSensors($module, $sensorTypes, $entityManager);
        $module->setUpdatedAt(new \DateTimeImmutable());
    }

    /**
     * Creates `Sensors` for the `Module` given. 
     *
     * @param Module $module
     * @param array[SensorType] $sensorTypes
     * @param EntityManager $entityManager
     * @return void
     */
    private function createSensors(Module $module, array $sensorTypes, EntityManager $entityManager): void
    {
        if (empty($sensorTypes)) return;
        foreach ($sensorTypes as $sensorType) {
            $sensor = new Sensor();
            $sensor->initializeSensor($sensorType, $module);
            $entityManager->persist($sensor);
        }
    }
}
