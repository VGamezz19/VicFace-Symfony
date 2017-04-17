<?php

namespace AppBundle\Controller;

use AppBundle\AppBundle;
use AppBundle\Entity\Comentario;
use AppBundle\Entity\Image;
use AppBundle\Entity\Articulo;
use AppBundle\Entity\Notificacion;
use AppBundle\Form\ArticuloType;
use AppBundle\Form\ImageType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Trascastro\UserBundle\Entity\User;


//________________________________________________________________________
//Home View
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
                'articulos' => $articulos

            ]);

    }

//________________________________________________________________________
//Start Articulo

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


            return $this->redirectToRoute('app_index_index'); //para generar otravez la tabla principal
        }

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
    public function deleteAction (Articulo $articulo) {

        if ($this->getUser() == $articulo->getUser() or $this->isGranted('ROLE_SUPER_ADMIN')){
            $m = $this->getDoctrine()->getManager();


            $comentario = $articulo->getComentarios();
            $comentariosArray = count($comentario);

            for($i=0; $i<$comentariosArray; $i++) {

                $m->remove($comentario[$i]);
            }

            $notificaciones = $articulo->getNotificacionArticulo();
            $notificacionesArray = count($notificaciones);

            for($i=0; $i<$notificacionesArray; $i++) {

                $m->remove($notificaciones[$i]);
            }

            $m->remove($articulo);
            $m->flush();

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

        if ($p->getUser() == $this->getUser() or $this->isGranted('ROLE_SUPER_ADMIN')){
            return $this->render(':index:upload.html.twig',
                [
                    'articulo' => $p,
                    'form' => $form->createView(),
                    'headTitle' => 'Create Article',
                    'action' => $this->generateUrl('app_articulo_updateAction', ['id' => $id])
                ]);
        } else {
            return $this->redirectToRoute('app_index_index');
        }

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
            return $this->redirectToRoute('app_index_index');
        }

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
//________________________________________________________________________
//End Articulo


//________________________________________________________________________
//Start Comentario

    /**
     * @Route("/createActionComentario/{id}", name="app_comentario_createAction")
     *
     */
    public function doCreateComment (Request $request, Articulo $id){
        //buscamos el articulo que esta haciendo el nuevo comentario
        $m = $this->getDoctrine()->getManager();
        $report = $m->getRepository('AppBundle:Articulo');
        $articulo = $report->find($id);

        //Si es el mismo usuario, no crearemos una notificacion.
        if ($articulo->getUser() == $this->getUser()){

            $comentario = new Comentario();

            //Rellenamos el COMENTARIO
            $comentario->setComentario($request->request->get('comentarioInput'));
            $comentario->setOwner($this->getUser()); //Añadimos el owner del comentario
            $comentario->setArticuloOwner($articulo); //le pasamos el ID del form.

            $m = $this->getDoctrine()->getManager();

            $m->persist($comentario); //push
            $m->flush();

        } else {

            $notificacion = new Notificacion();
            $comentario = new Comentario();

            //Rellenamos el COMENTARIO
            $comentario->setComentario($request->request->get('comentarioInput'));
            $comentario->setOwner($this->getUser()); //Añadimos el owner del comentario
            $comentario->setArticuloOwner($articulo); //le pasamos el ID del form.

            //Rellenamos la NOTIFICACION
            $notificacion->setArticuloNotificacion($articulo);
            $notificacion->setOwnerNotificacion($this->getUser());
            $notificacion->setNotificacionLlegadaUsuario($articulo->getUser());

            $m = $this->getDoctrine()->getManager();

            $m->persist($comentario); //push
            $m->persist($notificacion); //push
            $m->flush();

        }

        return $this->redirectToRoute('app_index_index'); //para generar otravez la tabla principal
    }

    /**
     * @Route("/deleteComment/{id}", name="app_comentario_delete")
     *
     * @ParamConverter(name="comentario", class="AppBundle:Comentario")
     */
    public function deleteComentario ( Comentario $comentario) {

        if ($this->getUser() == $comentario->getOwner() or $this->isGranted('ROLE_SUPER_ADMIN')){


            $m = $this->getDoctrine()->getManager();
            $m->remove($comentario);
            $m->flush();

            return $this->redirectToRoute('app_index_index');
        } else {
            return $this->redirectToRoute('app_index_index');
        }

    }

//________________________________________________________________________
//End Comentario


//________________________________________________________________________
//Start Notificacion
    /**
     * @Route("/deleteNotificacion/{id}", name="app_notificacion_delete")
     *
     * @ParamConverter(name="articulo", class="AppBundle:Articulo")
     */
    public function deletNotificacion ( Articulo $articulo) {

        if ($this->getUser() == $articulo->getUser()){

            $m = $this->getDoctrine()->getManager();
            $notificacion = $articulo->getNotificacionArticulo();

            $arr_length = count($notificacion);

            for($i=0; $i<$arr_length; $i++) {
                $m->remove($notificacion[$i]);
            }

            $m->flush();

            return $this->redirectToRoute('app_index_index');
        } else {
            return $this->redirectToRoute('app_index_index');
        }

    }

    /**
     * @Route("/deleteNotificacionSingle/{id}", name="app_notificacionSingle_delete")
     *
     * @ParamConverter(name="notificacion", class="AppBundle:Notificacion")
     */
    public function deleteSingleNotificacion ( Notificacion $notificacion) {

        if ($this->getUser() == $notificacion->getNotificacionLlegadaUsuario()){

            $m = $this->getDoctrine()->getManager();
            $m->remove($notificacion);
            $m->flush();

            return $this->redirectToRoute('app_index_index');
        } else {
            return $this->redirectToRoute('app_index_index');
        }

    }

//________________________________________________________________________
//End Notificacion


}
