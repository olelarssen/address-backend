<?php

namespace App\Service;

use App\Entity\Log;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ApiService
{

    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(RegistryInterface $doctrine)
    {
        $this->em = $doctrine->getEntityManager();
    }

    protected function log(string $string): bool
    {
        if (is_null($string)) {
            return false;
        }
        $log = new Log();
        $log->setRequest($string);

        try {
            $this->em->persist($log);
            $this->em->flush();
        } catch (ORMException $e) {
            return false;
        }

        return true;
    }

    public function getData(string $string): array
    {
        if ($this->log($string)) {
            $data = file_get_contents("https://maps.googleapis.com/maps/api/place/autocomplete/json?input=".
                                      $string.
                                      "&types=geocode&key=".getenv('API_KEY'));
            $arr  = [];
            $i    = 0;
            foreach (json_decode($data)->predictions as $item) {
                $arr[$i] = [
                    'id'   => $i,
                    'text' => $item->description
                ];
                $i++;
            }

            return $arr;
        }

    }
}