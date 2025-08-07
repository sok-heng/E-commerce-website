<?php
$main_categories = [
    "Skincare" => ["Cleansers", "Moisturizers", "Serums"],
    "Makeup" => ["Foundation", "Lipstick", "Mascara"]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet" />
 <script src="https://cdn.tailwindcss.com"></script>
<!-- Flowbite Script -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>

</head>
<body>
    <div class="relative inline-block">
    <!-- Main Dropdown Button -->
    <button id="dropdownButton" data-dropdown-toggle="mainDropdown"
        class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none font-medium rounded-lg text-sm px-4 py-2.5 text-center inline-flex items-center"
        type="button">
        Select Category
        <svg class="w-4 h-4 ml-2" aria-hidden="true" fill="none" stroke="currentColor"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    <!-- Main Dropdown -->
    <div id="mainDropdown"
        class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownButton">
            <?php foreach ($main_categories as $main => $subs): ?>
                <li class="relative group">
                    <button type="button"
                        class="flex items-center w-full px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white justify-between"
                        data-dropdown-toggle="dropdown-<?= strtolower($main) ?>">
                        <?= htmlspecialchars($main) ?>
                        <svg class="w-4 h-4 ml-2" aria-hidden="true" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>

                    <!-- Sub Dropdown -->
                    <div id="dropdown-<?= strtolower($main) ?>"
                        class="hidden absolute left-full top-0 z-10 w-44 bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700 group-hover:block">
                        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200">
                            <?php foreach ($subs as $sub): ?>
                                <li>
                                    <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                        <?= htmlspecialchars($sub) ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
</body>
</html>
