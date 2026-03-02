<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AccessController extends AbstractController
{
    #[Route('/acces', name: 'app_access', methods: ['GET'])]
    public function index(): Response
    {
        $apiKey = trim((string) $this->getParameter('app.google_maps_api_key'));
        $latitude = trim((string) $this->getParameter('app.pizzeria_map_latitude'));
        $longitude = trim((string) $this->getParameter('app.pizzeria_map_longitude'));
        $address = trim((string) $this->getParameter('app.pizzeria_map_address'));

        $destination = sprintf('%s,%s', $latitude, $longitude);
        $encodedDestination = rawurlencode($destination);

        $googleEmbedUrl = $apiKey !== ''
            ? sprintf(
                'https://www.google.com/maps/embed/v1/place?key=%s&q=%s&zoom=16',
                rawurlencode($apiKey),
                $encodedDestination
            )
            : null;

        $lat = (float) $latitude;
        $lng = (float) $longitude;
        $delta = 0.01;
        $osmEmbedUrl = sprintf(
            'https://www.openstreetmap.org/export/embed.html?bbox=%1$.6F%%2C%2$.6F%%2C%3$.6F%%2C%4$.6F&layer=mapnik&marker=%5$.6F%%2C%6$.6F',
            $lng - $delta,
            $lat - $delta,
            $lng + $delta,
            $lat + $delta,
            $lat,
            $lng
        );

        $directionsUrl = sprintf('https://www.google.com/maps/dir/?api=1&destination=%s', $encodedDestination);

        return $this->render('pages/access.html.twig', [
            'mapEmbedUrl' => $googleEmbedUrl ?? $osmEmbedUrl,
            'isGoogleMapEnabled' => $googleEmbedUrl !== null,
            'directionsUrl' => $directionsUrl,
            'address' => $address,
        ]);
    }
}
