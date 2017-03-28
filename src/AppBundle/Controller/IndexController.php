<?php

namespace AppBundle\Controller;

use AppBundle\AppBundle;
use AppBundle\Entity\Image;
use AppBundle\Entity\Articulo;
use AppBundle\Form\ArticuloType;
use AppBundle\Form\ImageType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Trascastro\UserBundle\Entity\User;


class IndexController extends Controller
{
    /**
     * @Route("/", name="app_index_index")
     */
    public function indexAction()
    {

        $m = $this->getDoctrine()->getManager();
        $report = $m->getRepository('AppBundle:Articulo');
        $articulos = $report->findAll();

        return $this->render('index/index.html.twig',
            [
                'articulos' => $articulos,
            ]);

    }


    /**
     * @Route("/create", name="app_articulo_create")
     *
     */
    public function createAction (){

        $articulo = new Articulo();
        $form = $this->createForm(ArticuloType::class, $articulo);

        return $this->render(':index:upload.html.twig',
            [
                'form' => $form->createView(),
                'headTitle' => 'Create Article',
                'action' => $this->generateUrl('app_articulo_createAction')

            ]

        );
    }
    /**
     * @Route("/createAction/", name="app_articulo_createAction")
     *
     */
    public function doCreateAction (Request $request){

        $articulo = new Articulo();
        $form = $this->createForm(ArticuloType::class, $articulo);
        $form->handleRequest($request);
        if ($form -> isValid()){
            $m = $this->getDoctrine()->getManager();

            $articulo->setUser($this->getUser()); //Añadimos el usuario

            $m->persist($articulo);
            $m->flush();
            $this->addFlash('messages', 'Producto añadido TETE');

            return $this->redirectToRoute('app_index_index'); //para generar otravez la tabla principal
        }
        $this->addFlash('messages', 'XAVAL! QUE TE HAS "EJIBOJAO"');
        return $this->render(':index:upload.html.twig',
            [
                'form' => $form->createView(),
                'action' => $this->generateUrl('app_articulo_createAction')

            ]);
    }

    /**
     * @Route("/delete/{id}", name="app_articulo_delete")
     *
     * @ParamConverter(name="articulo", class="AppBundle:Articulo")
     */
    public function deleteAction ($articulo) {

        if ($this->getUser() == $articulo->getUser()){
            $m = $this->getDoctrine()->getManager();
            $m->remove($articulo);
            $m->flush();
            $this->addFlash('messages', 'Producto eliminao CHAACHO');

            return $this->redirectToRoute('app_index_index');
        } else {
            return $this->redirectToRoute('app_index_index');
        }

    }


    /**
     *
     * @Route("/update/{id}", name="app_articulo_update")
     *
     */
    public function updateAction(Request $request, $id)
    {

        $m = $this->getDoctrine()->getManager();
        $report = $m->getRepository('AppBundle:Articulo');
        $p = $report->find($id);
        $form = $this->createForm(ArticuloType::class, $p);
        return $this->render(':index:upload.html.twig',
            [
                'articulo' => $p,
                'form' => $form->createView(),
                'headTitle' => 'Create Article',
                'action' => $this->generateUrl('app_articulo_updateAction', ['id' => $id])
            ]);
    }
    /**
     * @Route("/updateAction/{id}", name="app_articulo_updateAction")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function doUpdateAction(Request $request, $id)
    {
        $m = $this->getDoctrine()->getManager();
        $report = $m->getRepository('AppBundle:Articulo');
        $p = $report->find($id);
        $form = $this->createForm(ArticuloType::class, $p);
        $form->handleRequest($request);
        if ($form->isValid()){
            $m->flush();
            $this->addFlash('messages', 'Producto UPDATEAO PAYOOO');
            return $this->redirectToRoute('app_index_index');
        }
        $this->addFlash('messages','Tu eres tonto o que payaso? caranchoa');
        return $this->render(':index:upload.html.twig',
            [
                'form' => $form->createView(),
                'action' => $this->generateUrl('app_articulo_updateAction', ['id' => $id])

            ]);
    }


    /**
     * @Route("/single/{id}", name="app_articulo_single")
     *
     */
    public function singleUser (User $user) {
       // $m = $this->getDoctrine()->getManager();
        //$report = $m->getRepository('UserBundle:User');
        //$usuario = $report->findOneBy(array('user_id' => $usuarioID));

        return $this->render(':index:singleUser.html.twig',
            [
                'usuario' => $user ,
                'articulos' =>$user->getArticulos()
            ]);
    }

    /**
     * @Route("/upload", name="app_index_upload")
     */
    public function uploadAction(Request $request)
    {
        $p = new Image();
        $form = $this->createForm(ImageType::class, $p);

        if ($request->getMethod() == Request::METHOD_POST) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $m = $this->getDoctrine()->getManager();
                $m->persist($p);
                $m->flush();

                return $this->redirectToRoute('app_index_index');
            }
        }

        return $this->render(':index:upload.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
