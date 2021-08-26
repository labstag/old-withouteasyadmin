<?php

namespace Labstag\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use tidy;

class KernelSubscriber implements EventSubscriberInterface
{
    public const TAGS               = [
        'workflow-action',
        'link-show',
        'link-guard',
        'link-add',
        'link-edit',
        'link-delete',
        'link-restore',
        'link-destroy',
        'link-trash',
        'link-btnadmin',
        'link-empty',
        'link-move',
        'link-btnadminempty',
        'link-btnadminempties',
        'link-btnadmindeleties',
        'link-btnadminemptyall',
        'link-btnadminrestore',
        'link-btnadminrestories',
        'link-btnadmindestroy',
        'link-btnadmindelete',
        'link-btnadminmove',
        'guard-route',
        'guard-workflow',
        'guard-setworkflow',
        'guard-setroute',
        'guard-allworkflow',
        'guard-allroute',
        'guard-changeworkflow',
        'guard-changeroute',
        'guard-refgrouproute',
        'guard-refgroupworkflow',
        'confirm-delete',
        'confirm-deleteattachment',
        'confirm-destroy',
        'confirm-restore',
        'confirm-restories',
        'confirm-empty',
        'confirm-emptyall',
        'confirm-empties',
        'confirm-deleties',
        'confirm-workflow',
        'attachment-img',
        'attachment-delete',
        'btn-addcollection',
        'btn-delete',
        'btn-togglefieldset',
        'select-country',
        'select-selector',
        'select-refuser',
        'input-phone',
        'input-email',
        'input-url',
        'input-gps',
        'input-codepostal',
        'input-ville',
        'table-datatable',
        'select-all',
        'select-element',
    ];
    public const LABSTAG_CONTROLLER = '/(Labstag)/';

    public const ERRORNUMBER = 500;

    public const ERROR_CONTROLLER = [
        'error_controller',
        'error_controller::preview',
    ];

    public function onKernelResponse($event)
    {
        $response   = $event->getResponse();
        $request    = $event->getRequest();
        $controller = $request->attributes->get('_controller');
        preg_match(self::LABSTAG_CONTROLLER, $controller, $matches);
        if (0 == count($matches) || in_array($controller, self::ERROR_CONTROLLER)) {
            return;
        }

        if ('html' != $request->getRequestFormat() || self::ERRORNUMBER == $response->getStatusCode()) {
            return;
        }

        $content = $response->getContent();
        $config  = [
            'indent'                      => true,
            'indent-spaces'               => 2,
            'output-xhtml'                => true,
            'drop-empty-elements'         => false,
            'drop-proprietary-attributes' => false,
            'new-inline-tags'             => implode(' ', self::TAGS),
            'wrap'                        => 200,
        ];
        $tidy    = new tidy();
        $tidy->parseString($content, $config, 'utf8');
        $tidy->cleanRepair();
        $response->setContent($tidy);
        $event->setResponse($response);
    }

    public static function getSubscribedEvents()
    {
        return ['kernel.response' => 'onKernelResponse'];
    }
}
