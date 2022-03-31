<?php 
namespace App\Controller;

use App\Entity\Videos;
use App\Form\VideosFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\Interfaces\TricksVideosManagementInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TricksVideosController extends AbstractController
{
    private TricksVideosManagementInterface $iVideos;

    public function __construct(TricksVideosManagementInterface $iVideos)
    {
        $this->iVideos = $iVideos;
    }

    /**
     * @Route("/update-video-{id}", name="update_video", methods={"GET", "POST"})
     *
     * @param Request $request
     * @param Videos $video
     * @return Response
     */
    public function updateVideo(Request $request, Videos $video): Response
    {
        $form = $this->createForm(VideosFormType::class, $video);
        $form->handleRequest($request);

        $last =$video->getId();

        if ($form->isSubmitted() && $form->isValid()) {
            $this->iVideos->updateVideo($video, $form);

            $this->addFlash('success', 'La vidéo n° '.$last.' a été correctement modifié');

            return $this->redirectToRoute('trick_update', ['id' => $video->getTrick()->getId()]);
        }

        return $this->render('videos/edit.html.twig', [
            'title' => 'Modifiez cette url de vidéo',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete-video-{id}", name="delete_video", methods={"POST"})
     *
     * @param Request $request
     * @param Videos $video
     * @return Response
     */
    public function delete(Request $request, Videos $video): Response
    {
        $tokeId = (string) "'delete' . $video->getId()";

        if ($this->isCsrfTokenValid($tokeId , $request->request->get('_token'))) {
            $lastVideo = $video->getId();

            $this->iVideos->deleteVideo($video);

            $this->addFlash(
                'success',
                'La vidéo n° '. $lastVideo .' a été correctement supprimée'
            );

            return $this->redirectToRoute('trick_update', ['id' => $video->getTrick()->getId()]);
        }

        return $this->redirectToRoute('trick_update', ['id' => $video->getTrick()->getId()]);
    }
}
