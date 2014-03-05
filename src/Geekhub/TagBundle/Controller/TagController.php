<?php

namespace Geekhub\TagBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class TagController extends Controller
{
    public function getTagsAction()
    {
        // Remove after fixtures will be written
        $this->get('fpn_tag.tag_manager')->loadOrCreateTags(array('foo', 'bar', 'hello', 'world'));

        $tags = $this->getDoctrine()->getRepository('TagBundle:Tag')->findAll();

        return new Response($this->get('jms_serializer')->serialize($tags, 'json'));
    }
}
