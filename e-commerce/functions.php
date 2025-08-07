<?php
function renderStars($rating, $maxStars = 5) {
    $fullStars = floor($rating);
    $halfStar = ($rating - $fullStars) >= 0.5 ? 1 : 0;
    $emptyStars = $maxStars - $fullStars - $halfStar;

    $starsHtml = '';

    $svgFull = '<svg class="h-6 w-6 text-yellow-400 inline-block" fill="currentColor" viewBox="0 0 22 20" xmlns="http://www.w3.org/2000/svg">
      <path d="M20.924 7.625a1.523 1.523 0 00-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 00-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 001.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 002.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 002.226-1.617l-.863-5.03 3.656-3.563a1.523 1.523 0 00.387-1.575z" />
    </svg>';

    $uniqueId = uniqid('halfGrad_');

    $svgHalf = '
    <svg class="h-6 w-6 text-yellow-400 inline-block" fill="currentColor" viewBox="0 0 22 20" xmlns="http://www.w3.org/2000/svg">
      <defs>
        <linearGradient id="' . $uniqueId . '">
          <stop offset="50%" stop-color="currentColor" />
          <stop offset="50%" stop-color="transparent" stop-opacity="1" />
        </linearGradient>
      </defs>
      <path fill="url(#' . $uniqueId . ')" d="M20.924 7.625a1.523 1.523 0 00-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 00-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 001.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 002.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 002.226-1.617l-.863-5.03 3.656-3.563a1.523 1.523 0 00.387-1.575z" />
    </svg>';

    $svgEmpty = '<svg class="h-6 w-6 text-gray-300 inline-block" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 22 20" xmlns="http://www.w3.org/2000/svg">
      <path d="M20.924 7.625a1.523 1.523 0 00-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 00-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 001.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 002.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 002.226-1.617l-.863-5.03 3.656-3.563a1.523 1.523 0 00.387-1.575z"/>
    </svg>';

    for ($i = 0; $i < $fullStars; $i++) {
        $starsHtml .= $svgFull;
    }

    if ($halfStar) {
        $starsHtml .= $svgHalf;
    }

    for ($i = 0; $i < $emptyStars; $i++) {
        $starsHtml .= $svgEmpty;
    }

    return $starsHtml;
}
