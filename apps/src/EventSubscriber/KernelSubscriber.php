<?php

namespace Labstag\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use tidy;

class KernelSubscriber implements EventSubscriberInterface
{
    public const API_CONTROLLER = '/(Api)/';

    public const CLIENTNUMBER = 400;

    public const ERROR_CONTROLLER = [
        'error_controller',
        'error_controller::preview',
    ];

    public const LABSTAG_CONTROLLER = '/(Labstag)/';

    public const TAGS = [
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
        'confirm-close',
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
        'input-phone',
        'input-email',
        'input-url',
        'input-gps',
        'input-codepostal',
        'input-city',
        'table-datatable',
        'select-all',
        'select-element',
    ];

    public static function getSubscribedEvents(): array
    {
        return ['kernel.response' => 'onKernelResponse'];
    }

    public function onKernelResponse($event)
    {
        $response   = $event->getResponse();
        $request    = $event->getRequest();
        $controller = $request->attributes->get('_controller');
        preg_match(self::LABSTAG_CONTROLLER, $controller, $matches);
        preg_match(self::API_CONTROLLER, $controller, $apis);
        if (0 == count($matches) || in_array($controller, self::ERROR_CONTROLLER) || 0 != count($apis)) {
            return;
        }

        if ('html' != $request->getRequestFormat() || $response->getStatusCode() >= self::CLIENTNUMBER) {
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
}
