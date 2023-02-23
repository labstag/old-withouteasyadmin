<?php

namespace Labstag\Service;

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberToCarrierMapper;
use libphonenumber\PhoneNumberToTimeZonesMapper;
use libphonenumber\PhoneNumberUtil;
use Psr\Log\LoggerInterface;

class PhoneService
{

    protected PhoneNumberUtil $phoneUtil;

    public function __construct(
        protected ErrorService $errorService,
        protected LoggerInterface $logger
    )
    {
        $this->phoneUtil = PhoneNumberUtil::getInstance();
    }

    /**
     * Verifie le numéro de téléphone en fonction du pays.
     *
     * @param null|string $numero Numéro de téléphone
     * @param null|string $locale code du pays
     *
     * @throws NumberParseException
     */
    public function verif(?string $numero, ?string $locale): array
    {
        $numero = str_replace([' ', '-', '.'], '', (string) $numero);
        $data = [];
        $phoneNumberToTimeZonesMapper = PhoneNumberToTimeZonesMapper::getInstance();
        $phoneNumberToCarrierMapper = PhoneNumberToCarrierMapper::getInstance();

        try {
            $parse = $this->phoneUtil->parse(
                $numero,
                strtoupper($locale)
            );
            $isvalid = $this->phoneUtil->isValidNumber($parse);

            $data['isvalid'] = $isvalid;
            $data['format'] = [
                'e164'          => $this->phoneUtil->format(
                    $parse,
                    PhoneNumberFormat::E164
                ),
                'national'      => $this->phoneUtil->format(
                    $parse,
                    PhoneNumberFormat::NATIONAL
                ),
                'international' => $this->phoneUtil->format(
                    $parse,
                    PhoneNumberFormat::INTERNATIONAL
                ),
            ];
            $data['timezones'] = $phoneNumberToTimeZonesMapper->getTimeZonesForNumber($parse);
            $data['carrier'] = $phoneNumberToCarrierMapper->getNameForNumber(
                $parse,
                strtoupper($locale)
            );
            $data['parse'] = $parse;
        } catch (NumberParseException $numberParseException) {
            $this->errorService->set($numberParseException);
            $data['error'] = $numberParseException->getMessage();
        }

        return $data;
    }
}
